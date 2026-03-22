<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use CodeIgniter\HTTP\RedirectResponse;

class Customers extends BaseController
{
    protected CustomerModel $customerModel;

    public function __construct()
    {
        $this->customerModel = model(CustomerModel::class);
    }

    /**
     * List customers with optional search (name, phone, email).
     */
    public function index(): string
    {
        helper('form');
        $q = $this->request->getGet('q');
        $builder = $this->customerModel->builder();

        if ($q !== null && $q !== '') {
            $builder->groupStart()
                ->like('name', $q)
                ->orLike('phone', $q)
                ->orLike('email', $q)
                ->groupEnd();
        }

        $sortCol  = $this->request->getGet('sort');
        $sortOrder = strtolower((string) $this->request->getGet('order')) === 'desc' ? 'desc' : 'asc';
        $allowedSort = ['customer_type', 'name', 'phone', 'email', 'city', 'is_active'];
        if ($sortCol !== null && in_array($sortCol, $allowedSort, true)) {
            $builder->orderBy($sortCol, $sortOrder);
        } else {
            $builder->orderBy('name', 'asc');
        }
        $customers = $builder->get()->getResultArray();

        $data = [
            'pageTitle' => 'Customers - Gameshala ERP',
            'customers' => $customers,
            'searchQ'    => $q ?? '',
            'sort'      => $sortCol ?? 'name',
            'order'     => $sortCol !== null && in_array($sortCol, $allowedSort, true) ? $sortOrder : 'asc',
        ];

        return view('layout/main', [
            'pageTitle' => $data['pageTitle'],
            'content'   => view('sales/customers/index', $data),
        ]);
    }

    /**
     * Add new customer (POST). Mandatory: phone, customer_type, name. is_active defaults to 1.
     */
    public function add(): RedirectResponse
    {
        $rules = [
            'customer_type'  => 'required|in_list[INDIVIDUAL,BUSINESS,WALK_IN]',
            'name'           => 'required|max_length[150]',
            'phone'          => 'required|max_length[30]',
            'email'          => 'permit_empty|max_length[191]',
            'address_line1'  => 'permit_empty|max_length[255]',
            'address_line2'  => 'permit_empty|max_length[255]',
            'city'           => 'permit_empty|max_length[100]',
            'state'          => 'permit_empty|max_length[100]',
            'postal_code'    => 'permit_empty|max_length[20]',
            'tax_number'     => 'permit_empty|max_length[100]',
            'notes'          => 'permit_empty|max_length[500]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'customer_type' => $this->request->getPost('customer_type'),
            'name'          => $this->request->getPost('name'),
            'phone'         => $this->request->getPost('phone'),
            'email'         => $this->request->getPost('email') ?: null,
            'address_line1' => $this->request->getPost('address_line1') ?: null,
            'address_line2' => $this->request->getPost('address_line2') ?: null,
            'city'          => $this->request->getPost('city') ?: null,
            'state'         => $this->request->getPost('state') ?: null,
            'postal_code'   => $this->request->getPost('postal_code') ?: null,
            'tax_number'    => $this->request->getPost('tax_number') ?: null,
            'notes'         => $this->request->getPost('notes') ?: null,
            'is_active'     => 1,
        ];

        $id = $this->customerModel->insert($data);
        $this->logActivity('sales', 'customer_create', (int) $id, 'Created customer: ' . $data['name']);
        return redirect()->back()->with('message', 'Customer added successfully.');
    }

    /**
     * Update customer (POST). Mandatory: phone, customer_type, name.
     */
    public function update(int $id): RedirectResponse
    {
        $customer = $this->customerModel->find($id);
        if (! $customer) {
            return redirect()->back()->with('error', 'Customer not found.');
        }

        $rules = [
            'customer_type' => 'required|in_list[INDIVIDUAL,BUSINESS,WALK_IN]',
            'name'          => 'required|max_length[150]',
            'phone'         => 'required|max_length[30]',
            'email'         => 'permit_empty|max_length[191]',
            'address_line1' => 'permit_empty|max_length[255]',
            'address_line2' => 'permit_empty|max_length[255]',
            'city'          => 'permit_empty|max_length[100]',
            'state'         => 'permit_empty|max_length[100]',
            'postal_code'   => 'permit_empty|max_length[20]',
            'tax_number'    => 'permit_empty|max_length[100]',
            'notes'         => 'permit_empty|max_length[500]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'customer_type' => $this->request->getPost('customer_type'),
            'name'          => $this->request->getPost('name'),
            'phone'         => $this->request->getPost('phone'),
            'email'         => $this->request->getPost('email') ?: null,
            'address_line1' => $this->request->getPost('address_line1') ?: null,
            'address_line2' => $this->request->getPost('address_line2') ?: null,
            'city'          => $this->request->getPost('city') ?: null,
            'state'         => $this->request->getPost('state') ?: null,
            'postal_code'   => $this->request->getPost('postal_code') ?: null,
            'tax_number'    => $this->request->getPost('tax_number') ?: null,
            'notes'         => $this->request->getPost('notes') ?: null,
        ];

        $this->customerModel->update($id, $data);
        $this->logActivity('sales', 'customer_update', $id, 'Updated customer: ' . $data['name']);
        return redirect()->back()->with('message', 'Customer updated successfully.');
    }

    /**
     * Set customer active (1) or inactive (0). POST.
     */
    public function setStatus(int $id): RedirectResponse
    {
        $customer = $this->customerModel->find($id);
        if (! $customer) {
            return redirect()->back()->with('error', 'Customer not found.');
        }

        $status = (int) $this->request->getPost('is_active');
        $status = $status === 1 ? 1 : 0;
        $this->customerModel->update($id, ['is_active' => $status]);
        $action = $status === 1 ? 'customer_activate' : 'customer_deactivate';
        $this->logActivity('sales', $action, $id, ($status === 1 ? 'Activated' : 'Deactivated') . ' customer: ' . ($customer['name'] ?? '#' . $id));

        $msg = $status === 1 ? 'Customer marked active.' : 'Customer marked inactive.';
        return redirect()->back()->with('message', $msg);
    }
}
