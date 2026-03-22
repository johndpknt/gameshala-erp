<?php

namespace App\Controllers;

use App\Models\CouponModel;
use CodeIgniter\HTTP\RedirectResponse;

class Coupons extends BaseController
{
    protected CouponModel $couponModel;

    public function __construct()
    {
        $this->couponModel = model(CouponModel::class);
    }

    /**
     * List coupons with optional search.
     */
    public function index(): string
    {
        helper('form');
        $q = $this->request->getGet('q');
        $builder = $this->couponModel->builder();

        if ($q !== null && $q !== '') {
            $builder->like('code', $q);
        }

        $sortCol  = $this->request->getGet('sort');
        $sortOrder = strtolower((string) $this->request->getGet('order')) === 'desc' ? 'desc' : 'asc';
        $allowedSort = ['code', 'discount_type', 'discount_value', 'min_order_amount', 'max_discount_amount', 'valid_from', 'valid_to', 'used_count', 'is_active'];
        if ($sortCol !== null && in_array($sortCol, $allowedSort, true)) {
            $builder->orderBy($sortCol, $sortOrder);
        } else {
            $builder->orderBy('code', 'asc');
        }
        $coupons = $builder->get()->getResultArray();

        $data = [
            'pageTitle' => 'Coupons - Gameshala ERP',
            'coupons'   => $coupons,
            'searchQ'   => $q ?? '',
            'sort'      => $sortCol ?? 'code',
            'order'     => $sortCol !== null && in_array($sortCol, $allowedSort, true) ? $sortOrder : 'asc',
        ];

        return view('layout/main', [
            'pageTitle' => $data['pageTitle'],
            'content'   => view('catalog/coupons/index', $data),
        ]);
    }

    /**
     * Add new coupon (POST). is_active defaults to 1, not validated from form.
     */
    public function add(): RedirectResponse
    {
        $rules = [
            'code'               => 'required|max_length[100]|is_unique[coupons.code]',
            'discount_type'      => 'required|in_list[FLAT,PERCENTAGE]',
            'discount_value'     => 'required|decimal',
            'min_order_amount'   => 'permit_empty|decimal',
            'max_discount_amount'=> 'permit_empty|decimal',
            'usage_limit'        => 'permit_empty|integer',
            'valid_from'         => 'required|valid_date',
            'valid_to'           => 'required|valid_date',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $validFrom = $this->normalizeDatetime($this->request->getPost('valid_from'));
        $validTo   = $this->normalizeDatetime($this->request->getPost('valid_to'));
        if (strtotime($validTo) < strtotime($validFrom)) {
            return redirect()->back()->withInput()->with('errors', ['valid_to' => 'Valid to must be after valid from.']);
        }

        $data = [
            'code'                => $this->request->getPost('code'),
            'discount_type'       => $this->request->getPost('discount_type'),
            'discount_value'      => (float) $this->request->getPost('discount_value'),
            'min_order_amount'    => $this->request->getPost('min_order_amount') !== '' ? (float) $this->request->getPost('min_order_amount') : null,
            'max_discount_amount' => $this->request->getPost('max_discount_amount') !== '' ? (float) $this->request->getPost('max_discount_amount') : null,
            'usage_limit'         => $this->request->getPost('usage_limit') !== '' ? (int) $this->request->getPost('usage_limit') : null,
            'used_count'          => 0,
            'valid_from'          => $validFrom,
            'valid_to'            => $validTo,
            'is_active'           => 1,
        ];

        $id = $this->couponModel->insert($data);
        $this->logActivity('catalog', 'coupon_create', (int) $id, 'Created coupon: ' . $data['code']);
        return redirect()->back()->with('message', 'Coupon added successfully.');
    }

    /**
     * Update coupon (POST).
     */
    public function update(int $id): RedirectResponse
    {
        $coupon = $this->couponModel->find($id);
        if (! $coupon) {
            return redirect()->back()->with('error', 'Coupon not found.');
        }

        $rules = [
            'code'               => "required|max_length[100]|is_unique[coupons.code,id,{$id}]",
            'discount_type'      => 'required|in_list[FLAT,PERCENTAGE]',
            'discount_value'     => 'required|decimal',
            'min_order_amount'   => 'permit_empty|decimal',
            'max_discount_amount'=> 'permit_empty|decimal',
            'usage_limit'        => 'permit_empty|integer',
            'valid_from'         => 'required|valid_date',
            'valid_to'           => 'required|valid_date',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $validFrom = $this->normalizeDatetime($this->request->getPost('valid_from'));
        $validTo   = $this->normalizeDatetime($this->request->getPost('valid_to'));
        if (strtotime($validTo) < strtotime($validFrom)) {
            return redirect()->back()->withInput()->with('errors', ['valid_to' => 'Valid to must be after valid from.']);
        }

        $data = [
            'code'                => $this->request->getPost('code'),
            'discount_type'       => $this->request->getPost('discount_type'),
            'discount_value'      => (float) $this->request->getPost('discount_value'),
            'min_order_amount'    => $this->request->getPost('min_order_amount') !== '' ? (float) $this->request->getPost('min_order_amount') : null,
            'max_discount_amount' => $this->request->getPost('max_discount_amount') !== '' ? (float) $this->request->getPost('max_discount_amount') : null,
            'usage_limit'         => $this->request->getPost('usage_limit') !== '' ? (int) $this->request->getPost('usage_limit') : null,
            'valid_from'          => $validFrom,
            'valid_to'            => $validTo,
        ];

        $this->couponModel->update($id, $data);
        $this->logActivity('catalog', 'coupon_update', $id, 'Updated coupon: ' . ($data['code'] ?? $coupon['code']));
        return redirect()->back()->with('message', 'Coupon updated successfully.');
    }

    /**
     * Set coupon active (1) or inactive (0). POST.
     */
    public function setStatus(int $id): RedirectResponse
    {
        $coupon = $this->couponModel->find($id);
        if (! $coupon) {
            return redirect()->back()->with('error', 'Coupon not found.');
        }

        $status = (int) $this->request->getPost('is_active');
        $status = $status === 1 ? 1 : 0;
        $this->couponModel->update($id, ['is_active' => $status]);
        $action = $status === 1 ? 'coupon_activate' : 'coupon_deactivate';
        $this->logActivity('catalog', $action, $id, ($status === 1 ? 'Activated' : 'Deactivated') . ' coupon: ' . ($coupon['code'] ?? '#' . $id));

        $msg = $status === 1 ? 'Coupon marked active.' : 'Coupon marked inactive.';
        return redirect()->back()->with('message', $msg);
    }

    /**
     * Normalize datetime from datetime-local input (Y-m-d\TH:i) to MySQL format (Y-m-d H:i:s).
     */
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
