<?php

namespace App\Controllers;

use App\Models\StockBatchModel;
use App\Models\ProductModel;
use App\Models\VendorModel;
use App\Models\ProcurementRuleModel;
use App\Models\BatchProcurementRuleModel;
use CodeIgniter\HTTP\RedirectResponse;

class StockBatches extends BaseController
{
    protected StockBatchModel $stockBatchModel;
    protected ProductModel $productModel;
    protected VendorModel $vendorModel;
    protected ProcurementRuleModel $procurementRuleModel;
    protected BatchProcurementRuleModel $batchRuleModel;

    public function __construct()
    {
        $this->stockBatchModel      = model(StockBatchModel::class);
        $this->productModel         = model(ProductModel::class);
        $this->vendorModel          = model(VendorModel::class);
        $this->procurementRuleModel = model(ProcurementRuleModel::class);
        $this->batchRuleModel       = model(BatchProcurementRuleModel::class);
    }

    /**
     * List stock batches with optional search (batch_code, product name, vendor name).
     */
    public function index(): string
    {
        helper('form');
        $q = $this->request->getGet('q');
        $db = $this->stockBatchModel->db;
        $prefix = $db->DBPrefix;
        $sb  = $prefix . 'stock_batches';
        $p   = $prefix . 'products';
        $v   = $prefix . 'vendors';
        $bpr = $prefix . 'batch_procurement_rules';
        $pr  = $prefix . 'procurement_rules';

        $subSelect = "(SELECT {$pr}.name FROM {$bpr} INNER JOIN {$pr} ON {$bpr}.procurement_rule_id = {$pr}.id WHERE {$bpr}.batch_id = {$sb}.id AND {$bpr}.is_active = 1 ORDER BY {$bpr}.id DESC LIMIT 1)";
        $builder = $this->stockBatchModel->builder()
            ->select("{$sb}.*, {$p}.name AS product_name, {$v}.name AS vendor_name, {$subSelect} AS rule_name", false)
            ->join($p, "{$sb}.product_id = {$p}.id", 'left')
            ->join($v, "{$sb}.vendor_id = {$v}.id", 'left');

        if ($q !== null && $q !== '') {
            $builder->groupStart()
                ->like("{$sb}.batch_code", $q)
                ->orLike("{$p}.name", $q)
                ->orLike("{$v}.name", $q)
                ->groupEnd();
        }

        $sortCol  = $this->request->getGet('sort');
        $sortOrder = strtolower((string) $this->request->getGet('order')) === 'asc' ? 'asc' : 'desc';
        $allowedSort = ['batch_code', 'product_name', 'vendor_name', 'purchased_qty', 'remaining_qty', 'unit_cost', 'received_at'];
        if ($sortCol !== null && in_array($sortCol, $allowedSort, true)) {
            $orderCol = $sortCol === 'product_name' ? "{$p}.name" : ($sortCol === 'vendor_name' ? "{$v}.name" : "{$sb}.{$sortCol}");
            $builder->orderBy($orderCol, $sortOrder);
        } else {
            $builder->orderBy("{$sb}.received_at", 'desc');
        }
        $batches = $builder->get()->getResultArray();

        $data = [
            'pageTitle' => 'Stock Batches - Gameshala ERP',
            'batches'   => $batches,
            'searchQ'   => $q ?? '',
            'sort'      => $sortCol ?? 'received_at',
            'order'     => $sortCol !== null && in_array($sortCol, $allowedSort, true) ? $sortOrder : 'desc',
        ];

        return view('layout/main', [
            'pageTitle' => $data['pageTitle'],
            'content'   => view('inventory/stock_batches/index', $data),
        ]);
    }

    /**
     * Show add batch form (separate page). Products and vendors for dropdowns.
     */
    public function add(): string
    {
        helper('form');
        $products = $this->productModel->where('is_active', 1)->orderBy('name', 'asc')->findAll();
        $vendors  = $this->vendorModel->where('is_active', 1)->orderBy('name', 'asc')->findAll();
        $rules    = $this->procurementRuleModel->where('is_active', 1)->orderBy('name', 'asc')->findAll();

        $data = [
            'pageTitle' => 'Add Stock Batch - Gameshala ERP',
            'products'  => $products,
            'vendors'   => $vendors,
            'rules'     => $rules,
            'batch'     => null,
        ];

        return view('layout/main', [
            'pageTitle' => $data['pageTitle'],
            'content'   => view('inventory/stock_batches/form', $data),
        ]);
    }

    /**
     * Process add batch (POST). Dedicated route so POST is never confused with GET.
     */
    public function create(): RedirectResponse
    {
        $rules = [
            'batch_code'    => 'required|max_length[100]|is_unique[stock_batches.batch_code]',
            'product_id'    => 'required|integer',
            'vendor_id'     => 'required|integer',
            'purchased_qty' => 'required|integer|greater_than_equal_to[0]',
            'remaining_qty' => 'required|integer|greater_than_equal_to[0]',
            'unit_cost'     => 'required|decimal',
            'received_at'   => 'required|valid_date',
            'remarks'       => 'max_length[500]|permit_empty',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $productId = (int) $this->request->getPost('product_id');
        $vendorId  = (int) $this->request->getPost('vendor_id');
        if (! $this->productModel->find($productId)) {
            return redirect()->back()->withInput()->with('errors', ['product_id' => 'Invalid product.']);
        }
        if (! $this->vendorModel->find($vendorId)) {
            return redirect()->back()->withInput()->with('errors', ['vendor_id' => 'Invalid vendor.']);
        }

        $receivedAt = $this->normalizeDatetime($this->request->getPost('received_at'));
        $purchased  = (int) $this->request->getPost('purchased_qty');
        $remaining  = (int) $this->request->getPost('remaining_qty');
        if ($remaining > $purchased) {
            return redirect()->back()->withInput()->with('errors', ['remaining_qty' => 'Remaining qty cannot exceed purchased qty.']);
        }

        $data = [
            'batch_code'    => $this->request->getPost('batch_code'),
            'product_id'    => $productId,
            'vendor_id'     => $vendorId,
            'purchased_qty' => $purchased,
            'remaining_qty' => $remaining,
            'unit_cost'     => (float) $this->request->getPost('unit_cost'),
            'received_at'   => $receivedAt,
            'remarks'       => $this->request->getPost('remarks') ?: null,
        ];

        $id = $this->stockBatchModel->insert($data);
        if ($id === false) {
            $errors = $this->stockBatchModel->errors();
            return redirect()->back()->withInput()->with('error', 'Failed to create stock batch: ' . (is_array($errors) ? implode(' ', $errors) : 'Unknown error'));
        }
        $id = (int) $id;
        $ruleId = $this->request->getPost('procurement_rule_id');
        if ($ruleId !== null && $ruleId !== '') {
            $ruleId = (int) $ruleId;
            if ($ruleId > 0 && $this->procurementRuleModel->find($ruleId)) {
                $this->batchRuleModel->insert([
                    'batch_id'             => $id,
                    'procurement_rule_id'  => $ruleId,
                    'applied_at'           => date('Y-m-d H:i:s'),
                    'is_active'            => 1,
                ]);
            }
        }
        $this->logActivity('inventory', 'batch_create', $id, 'Created stock batch: ' . $data['batch_code']);
        return redirect()->to('inventory/stock-batches')->with('message', 'Stock batch added successfully.');
    }

    /**
     * Show edit batch form.
     */
    public function edit(int $id): string|RedirectResponse
    {
        $batch = $this->stockBatchModel->find($id);
        if (! $batch) {
            return redirect()->to('inventory/stock-batches')->with('error', 'Stock batch not found.');
        }

        helper('form');
        $products = $this->productModel->where('is_active', 1)->orderBy('name', 'asc')->findAll();
        $vendors  = $this->vendorModel->where('is_active', 1)->orderBy('name', 'asc')->findAll();
        $rules    = $this->procurementRuleModel->where('is_active', 1)->orderBy('name', 'asc')->findAll();
        $currentRule = $this->batchRuleModel->where('batch_id', $id)->where('is_active', 1)->orderBy('id', 'desc')->first();
        $batch['current_procurement_rule_id'] = $currentRule ? (int) $currentRule['procurement_rule_id'] : null;

        $data = [
            'pageTitle' => 'Edit Stock Batch - Gameshala ERP',
            'products'  => $products,
            'vendors'   => $vendors,
            'rules'     => $rules,
            'batch'     => $batch,
        ];

        return view('layout/main', [
            'pageTitle' => $data['pageTitle'],
            'content'   => view('inventory/stock_batches/form', $data),
        ]);
    }

    /**
     * Process edit batch (POST). Dedicated route so POST is never confused with GET.
     */
    public function update(int $id): RedirectResponse
    {
        $batch = $this->stockBatchModel->find($id);
        if (! $batch) {
            return redirect()->to('inventory/stock-batches')->with('error', 'Stock batch not found.');
        }
        return $this->updateSubmit($id, $batch);
    }

    /**
     * Process edit batch (POST).
     */
    protected function updateSubmit(int $id, array $batch): RedirectResponse
    {
        $rules = [
            'batch_code'    => "required|max_length[100]|is_unique[stock_batches.batch_code,id,{$id}]",
            'product_id'    => 'required|integer',
            'vendor_id'     => 'required|integer',
            'purchased_qty' => 'required|integer|greater_than_equal_to[0]',
            'remaining_qty' => 'required|integer|greater_than_equal_to[0]',
            'unit_cost'     => 'required|decimal',
            'received_at'   => 'required|valid_date',
            'remarks'       => 'max_length[500]|permit_empty',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $productId = (int) $this->request->getPost('product_id');
        $vendorId  = (int) $this->request->getPost('vendor_id');
        if (! $this->productModel->find($productId)) {
            return redirect()->back()->withInput()->with('errors', ['product_id' => 'Invalid product.']);
        }
        if (! $this->vendorModel->find($vendorId)) {
            return redirect()->back()->withInput()->with('errors', ['vendor_id' => 'Invalid vendor.']);
        }

        $receivedAt = $this->normalizeDatetime($this->request->getPost('received_at'));
        $purchased  = (int) $this->request->getPost('purchased_qty');
        $remaining  = (int) $this->request->getPost('remaining_qty');
        if ($remaining > $purchased) {
            return redirect()->back()->withInput()->with('errors', ['remaining_qty' => 'Remaining qty cannot exceed purchased qty.']);
        }

        $data = [
            'batch_code'    => $this->request->getPost('batch_code'),
            'product_id'    => $productId,
            'vendor_id'     => $vendorId,
            'purchased_qty' => $purchased,
            'remaining_qty' => $remaining,
            'unit_cost'     => (float) $this->request->getPost('unit_cost'),
            'received_at'   => $receivedAt,
            'remarks'       => $this->request->getPost('remarks') ?: null,
        ];

        $this->stockBatchModel->update($id, $data);

        $newRuleId = $this->request->getPost('procurement_rule_id');
        $newRuleId = ($newRuleId !== null && $newRuleId !== '') ? (int) $newRuleId : null;
        $oldRule   = $this->batchRuleModel->where('batch_id', $id)->where('is_active', 1)->orderBy('id', 'desc')->first();
        $oldRuleId = $oldRule ? (int) $oldRule['procurement_rule_id'] : null;
        if ($newRuleId !== $oldRuleId) {
            $this->batchRuleModel->where('batch_id', $id)->set(['is_active' => 0])->update();
            if ($newRuleId !== null && $newRuleId > 0 && $this->procurementRuleModel->find($newRuleId)) {
                $this->batchRuleModel->insert([
                    'batch_id'            => $id,
                    'procurement_rule_id' => $newRuleId,
                    'applied_at'          => date('Y-m-d H:i:s'),
                    'is_active'           => 1,
                ]);
            }
        }

        $this->logActivity('inventory', 'batch_update', $id, 'Updated stock batch: ' . $data['batch_code']);
        return redirect()->to('inventory/stock-batches')->with('message', 'Stock batch updated successfully.');
    }

    protected function normalizeDatetime(?string $value): string
    {
        if ($value === null || $value === '') {
            return date('Y-m-d H:i:s');
        }
        $value = str_replace('T', ' ', $value);
        if (strlen($value) === 16) {
            $value .= ':00';
        }
        return $value;
    }
}
