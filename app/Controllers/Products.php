<?php

namespace App\Controllers;

use App\Models\ProductModel;
use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\HTTP\RedirectResponse;

class Products extends BaseController
{
    protected ProductModel $productModel;

    public function __construct()
    {
        $this->productModel = model(ProductModel::class);
    }

    /**
     * List products with optional search.
     */
    public function index(): string
    {
        helper(['form', 'text']);
        $q = $this->request->getGet('q');
        $builder = $this->productModel->builder();

        // Keep FOOD-/BEVE- products in the dedicated /catalog/beverage page.
        $builder->notLike('sku', 'FOOD-', 'after')
            ->notLike('sku', 'BEVE-', 'after');

        if ($q !== null && $q !== '') {
            $builder->groupStart()
                ->like('name', $q)
                ->orLike('sku', $q)
                ->orLike('slug', $q)
                ->orLike('description', $q)
                ->groupEnd();
        }

        $sortCol  = $this->request->getGet('sort');
        $sortOrder = strtolower((string) $this->request->getGet('order')) === 'desc' ? 'desc' : 'asc';
        $allowedSort = ['name', 'sku', 'slug', 'unit', 'is_public', 'is_active'];
        if ($sortCol !== null && in_array($sortCol, $allowedSort, true)) {
            $builder->orderBy($sortCol, $sortOrder);
        } else {
            $builder->orderBy('name', 'asc');
        }
        $products = $builder->get()->getResultArray();
        foreach ($products as &$p) {
            $p['image_urls'] = ProductModel::imageUrlToArray($p['image_url'] ?? null);
        }
        unset($p);

        $data = [
            'pageTitle' => 'Products - Gameshala ERP',
            'products'  => $products,
            'searchQ'   => $q ?? '',
            'sort'      => $sortCol ?? 'name',
            'order'     => $sortCol !== null && in_array($sortCol, $allowedSort, true) ? $sortOrder : 'asc',
        ];

        return view('layout/main', [
            'pageTitle' => $data['pageTitle'],
            'content'   => view('catalog/products/index', $data),
        ]);
    }

    /**
     * List only FOOD-/BEVE- catalog products.
     */
    public function beverage(): string
    {
        helper(['form', 'text']);
        $q = $this->request->getGet('q');
        $builder = $this->productModel->builder();

        $builder->groupStart()
            ->like('sku', 'FOOD-', 'after')
            ->orLike('sku', 'BEVE-', 'after')
            ->groupEnd();

        if ($q !== null && $q !== '') {
            $builder->groupStart()
                ->like('name', $q)
                ->orLike('sku', $q)
                ->orLike('slug', $q)
                ->orLike('description', $q)
                ->groupEnd();
        }

        $sortCol  = $this->request->getGet('sort');
        $sortOrder = strtolower((string) $this->request->getGet('order')) === 'desc' ? 'desc' : 'asc';
        $allowedSort = ['name', 'sku', 'slug', 'unit', 'is_public', 'is_active'];
        if ($sortCol !== null && in_array($sortCol, $allowedSort, true)) {
            $builder->orderBy($sortCol, $sortOrder);
        } else {
            $builder->orderBy('name', 'asc');
        }
        $products = $builder->get()->getResultArray();
        foreach ($products as &$p) {
            $p['image_urls'] = ProductModel::imageUrlToArray($p['image_url'] ?? null);
        }
        unset($p);

        $data = [
            'pageTitle'   => 'Beverage Catalog - Gameshala ERP',
            'pageHeading' => 'Beverage Catalog',
            'products'    => $products,
            'searchQ'     => $q ?? '',
            'sort'        => $sortCol ?? 'name',
            'order'       => $sortCol !== null && in_array($sortCol, $allowedSort, true) ? $sortOrder : 'asc',
            'listBase'    => base_url('catalog/beverage'),
        ];

        return view('layout/main', [
            'pageTitle' => $data['pageTitle'],
            'content'   => view('catalog/products/index', $data),
        ]);
    }

    /**
     * Add new product (POST).
     */
    public function add(): RedirectResponse
    {
        helper('text');
        $rules = [
            'sku'         => 'required|max_length[100]|is_unique[products.sku]',
            'name'        => 'required|max_length[200]',
            'slug'        => 'max_length[200]|permit_empty',
            'description' => 'permit_empty',
            'unit'        => 'required|max_length[50]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $files = $this->request->getFileMultiple('image');
        $paths = [];
        if (is_array($files)) {
            foreach ($files as $file) {
                if ($file === null || ! $file->isValid() || $file->hasMoved()) {
                    continue;
                }
                $err = $this->validateProductImage($file);
                if ($err !== null) {
                    return redirect()->back()->withInput()->with('errors', ['image' => $err]);
                }
                $path = $this->saveProductImage($file);
                if ($path !== null) {
                    $paths[] = $path;
                }
            }
        }
        if ($paths === []) {
            return redirect()->back()->withInput()->with('errors', ['image' => 'Upload at least one image.']);
        }
        $imageUrl = implode('|', $paths);

        $name = $this->request->getPost('name');
        $slug = $this->request->getPost('slug');
        if ($slug === null || trim($slug) === '') {
            $slug = url_title(convert_accented_characters($name), '-', true);
        }

        $data = [
            'sku'        => $this->request->getPost('sku'),
            'name'       => $name,
            'slug'       => $slug,
            'description' => $this->request->getPost('description') ?: null,
            'image_url'  => $imageUrl ?: null,
            'unit'       => $this->request->getPost('unit') ?: 'PCS',
            'is_public'  => (int) $this->request->getPost('is_public') ?: 1,
            'is_active'  => 1,
        ];

        $id = $this->productModel->insert($data);
        $this->logActivity('catalog', 'product_create', (int) $id, 'Created product: ' . $data['name']);
        return redirect()->back()->with('message', 'Product added successfully.');
    }

    /**
     * Update product (POST).
     */
    public function update(int $id): RedirectResponse
    {
        $product = $this->productModel->find($id);
        if (! $product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        $rules = [
            'sku'         => "required|max_length[100]|is_unique[products.sku,id,{$id}]",
            'name'        => 'required|max_length[200]',
            'slug'        => 'required|max_length[200]',
            'description' => 'permit_empty',
            'unit'        => 'required|max_length[50]',
            'is_public'   => 'in_list[0,1]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $existing = $product['image_url'] ?? '';
        $existingUrls = ProductModel::imageUrlToArray($existing);

        $files = $this->request->getFileMultiple('image');
        $newPaths = [];
        if (is_array($files)) {
            foreach ($files as $file) {
                if ($file === null || ! $file->isValid() || $file->hasMoved()) {
                    continue;
                }
                $err = $this->validateProductImage($file);
                if ($err !== null) {
                    return redirect()->back()->withInput()->with('errors', ['image' => $err]);
                }
                $path = $this->saveProductImage($file);
                if ($path !== null) {
                    $newPaths[] = $path;
                }
            }
        }
        $allPaths = array_merge($existingUrls, $newPaths);
        $imageUrl = $allPaths === [] ? null : implode('|', $allPaths);

        $data = [
            'sku'        => $this->request->getPost('sku'),
            'name'       => $this->request->getPost('name'),
            'slug'       => $this->request->getPost('slug'),
            'description' => $this->request->getPost('description') ?: null,
            'image_url'  => $imageUrl,
            'unit'       => $this->request->getPost('unit') ?: 'PCS',
            'is_public'  => (int) $this->request->getPost('is_public') ?: 1,
        ];

        $this->productModel->update($id, $data);
        $this->logActivity('catalog', 'product_update', $id, 'Updated product: ' . ($data['name'] ?? $product['name']));
        return redirect()->back()->with('message', 'Product updated successfully.');
    }

    /**
     * Set product active (1) or inactive (0). POST.
     */
    public function setStatus(int $id): RedirectResponse
    {
        $product = $this->productModel->find($id);
        if (! $product) {
            return redirect()->back()->with('error', 'Product not found.');
        }

        $status = (int) $this->request->getPost('is_active');
        $status = $status === 1 ? 1 : 0;
        $this->productModel->update($id, ['is_active' => $status]);
        $action = $status === 1 ? 'product_activate' : 'product_deactivate';
        $this->logActivity('catalog', $action, $id, ($status === 1 ? 'Activated' : 'Deactivated') . ' product: ' . ($product['name'] ?? '#' . $id));

        $msg = $status === 1 ? 'Product marked active.' : 'Product marked inactive.';
        return redirect()->back()->with('message', $msg);
    }

    /**
     * Validate uploaded product image. Returns error message or null if valid.
     */
    protected function validateProductImage(UploadedFile $file): ?string
    {
        if (! $file->isValid()) {
            return $file->getErrorString() ?: 'Invalid file upload.';
        }
        $maxSize = 2 * 1024 * 1024; // 2MB
        if ($file->getSize() > $maxSize) {
            return 'Image must be 2MB or smaller.';
        }
        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (! in_array($file->getMimeType(), $allowed, true)) {
            return 'Image must be JPEG, PNG, GIF or WebP.';
        }
        return null;
    }

    /**
     * Save uploaded file to assets/products and return relative path for image_url.
     */
    protected function saveProductImage(UploadedFile $file): ?string
    {
        $dir = FCPATH . 'assets/products';
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $ext = $file->getClientExtension() ?: $file->guessExtension();
        if (! $ext) {
            $ext = 'jpg';
        }
        $name = bin2hex(random_bytes(8)) . '.' . $ext;
        if (! $file->move($dir, $name)) {
            return null;
        }
        return 'assets/products/' . $name;
    }
}
