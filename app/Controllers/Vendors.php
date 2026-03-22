<?php

namespace App\Controllers;

use App\Models\VendorModel;
use CodeIgniter\HTTP\RedirectResponse;

class Vendors extends BaseController
{
    protected VendorModel $vendorModel;

    public function __construct()
    {
        $this->vendorModel = model(VendorModel::class);
    }

    /**
     * List vendors with optional search.
     */
    public function index(): string
    {
        helper('form');
        $q = $this->request->getGet('q');
        $builder = $this->vendorModel->builder();

        if ($q !== null && $q !== '') {
            $builder->groupStart()
                ->like('name', $q)
                ->orLike('email', $q)
                ->orLike('phone', $q)
                ->orLike('contact_person', $q)
                ->groupEnd();
        }

        $sortCol  = $this->request->getGet('sort');
        $sortOrder = strtolower((string) $this->request->getGet('order')) === 'desc' ? 'desc' : 'asc';
        $allowedSort = ['name', 'contact_person', 'phone', 'email', 'is_active'];
        if ($sortCol !== null && in_array($sortCol, $allowedSort, true)) {
            $builder->orderBy($sortCol, $sortOrder);
        } else {
            $builder->orderBy('name', 'asc');
        }
        $vendors = $builder->get()->getResultArray();

        $data = [
            'pageTitle' => 'Vendors - Gameshala ERP',
            'vendors'   => $vendors,
            'searchQ'   => $q ?? '',
            'sort'      => $sortCol ?? 'name',
            'order'     => $sortCol !== null && in_array($sortCol, $allowedSort, true) ? $sortOrder : 'asc',
        ];

        return view('layout/main', [
            'pageTitle' => $data['pageTitle'],
            'content'   => view('catalog/vendors/index', $data),
        ]);
    }

    /**
     * Add new vendor (POST).
     */
    public function add(): RedirectResponse
    {
        $rules = [
            'name' => 'required|max_length[150]',
            'contact_person' => 'max_length[150]',
            'phone' => 'max_length[30]',
            'email' => 'max_length[191]|permit_empty',
            'remarks' => 'max_length[500]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name'           => $this->request->getPost('name'),
            'contact_person' => $this->request->getPost('contact_person') ?: null,
            'phone'          => $this->request->getPost('phone') ?: null,
            'email'          => $this->request->getPost('email') ?: null,
            'remarks'        => $this->request->getPost('remarks') ?: null,
            'is_active'      => 1,
        ];

        $id = $this->vendorModel->insert($data);
        $this->logActivity('catalog', 'vendor_create', (int) $id, 'Created vendor: ' . $data['name']);
        return redirect()->back()->with('message', 'Vendor added successfully.');
    }

    /**
     * Update vendor (POST).
     */
    public function update(int $id): RedirectResponse
    {
        $vendor = $this->vendorModel->find($id);
        if (! $vendor) {
            return redirect()->back()->with('error', 'Vendor not found.');
        }

        $rules = [
            'name' => 'required|max_length[150]',
            'contact_person' => 'max_length[150]',
            'phone' => 'max_length[30]',
            'email' => 'max_length[191]|permit_empty',
            'remarks' => 'max_length[500]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name'           => $this->request->getPost('name'),
            'contact_person' => $this->request->getPost('contact_person') ?: null,
            'phone'          => $this->request->getPost('phone') ?: null,
            'email'          => $this->request->getPost('email') ?: null,
            'remarks'        => $this->request->getPost('remarks') ?: null,
        ];

        $this->vendorModel->update($id, $data);
        $this->logActivity('catalog', 'vendor_update', $id, 'Updated vendor: ' . ($data['name'] ?? $vendor['name']));
        return redirect()->back()->with('message', 'Vendor updated successfully.');
    }

    /**
     * Set vendor active (1) or inactive (0). POST.
     */
    public function setStatus(int $id): RedirectResponse
    {
        $vendor = $this->vendorModel->find($id);
        if (! $vendor) {
            return redirect()->back()->with('error', 'Vendor not found.');
        }

        $status = (int) $this->request->getPost('is_active');
        $status = $status === 1 ? 1 : 0;
        $this->vendorModel->update($id, ['is_active' => $status]);
        $action = $status === 1 ? 'vendor_activate' : 'vendor_deactivate';
        $this->logActivity('catalog', $action, $id, ($status === 1 ? 'Activated' : 'Deactivated') . ' vendor: ' . ($vendor['name'] ?? '#' . $id));

        $msg = $status === 1 ? 'Vendor marked active.' : 'Vendor marked inactive.';
        return redirect()->back()->with('message', $msg);
    }
}
