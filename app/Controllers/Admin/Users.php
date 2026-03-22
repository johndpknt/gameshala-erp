<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;

class Users extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = model(UserModel::class);
    }

    /**
     * List users with optional search and sort.
     */
    public function index(): string
    {
        helper('form');
        $q = $this->request->getGet('q');
        $sortCol   = $this->request->getGet('sort');
        $sortOrder = strtolower((string) $this->request->getGet('order')) === 'desc' ? 'desc' : 'asc';
        $allowedSort = ['name', 'email', 'role', 'is_active', 'last_login_at', 'created_at'];

        $builder = $this->userModel->builder();

        if ($q !== null && $q !== '') {
            $builder->groupStart()
                ->like('name', $q)
                ->orLike('email', $q)
                ->orLike('phone', $q)
                ->groupEnd();
        }

        if ($sortCol !== null && in_array($sortCol, $allowedSort, true)) {
            $builder->orderBy($sortCol, $sortOrder);
        } else {
            $builder->orderBy('name', 'asc');
        }
        $users = $builder->get()->getResultArray();

        return view('layout/main', [
            'pageTitle' => 'Users - Gameshala ERP',
            'content'   => view('admin/users/index', [
                'users'   => $users,
                'searchQ' => $q ?? '',
                'sort'    => $sortCol ?? 'name',
                'order'   => $sortCol !== null && in_array($sortCol, $allowedSort, true) ? $sortOrder : 'asc',
            ]),
        ]);
    }

    /**
     * Add new user (POST).
     */
    public function add(): RedirectResponse
    {
        $rules = [
            'name'     => 'required|max_length[150]',
            'email'    => 'required|valid_email|max_length[191]|is_unique[users.email]',
            'phone'    => 'permit_empty|max_length[30]',
            'password' => 'required|min_length[6]|max_length[72]',
            'role'     => 'required|in_list[ADMIN,STAFF]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $password = $this->request->getPost('password');
        $data = [
            'name'          => $this->request->getPost('name'),
            'email'         => $this->request->getPost('email'),
            'phone'         => $this->request->getPost('phone') ?: null,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'role'          => $this->request->getPost('role'),
            'is_active'     => 1,
        ];

        $id = $this->userModel->insert($data);
        if (! $id) {
            return redirect()->back()->withInput()->with('error', 'Failed to create user.');
        }
        $this->logActivity('admin', 'user_create', (int) $id, 'Created user: ' . $data['email']);
        return redirect()->back()->with('message', 'User added successfully.');
    }

    /**
     * Update user (POST). Password optional; if provided, it is updated.
     */
    public function update(int $id): RedirectResponse
    {
        $user = $this->userModel->find($id);
        if (! $user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        $rules = [
            'name'     => 'required|max_length[150]',
            'email'    => "required|valid_email|max_length[191]|is_unique[users.email,id,{$id}]",
            'phone'    => 'permit_empty|max_length[30]',
            'password' => 'permit_empty|min_length[6]|max_length[72]',
            'role'     => 'required|in_list[ADMIN,STAFF]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name'  => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone') ?: null,
            'role'  => $this->request->getPost('role'),
        ];
        $password = $this->request->getPost('password');
        if ($password !== null && $password !== '') {
            $data['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if (! $this->userModel->update($id, $data)) {
            return redirect()->back()->withInput()->with('error', 'Failed to update user.');
        }
        $this->logActivity('admin', 'user_update', $id, 'Updated user: ' . $data['email']);
        return redirect()->back()->with('message', 'User updated successfully.');
    }

    /**
     * Set user active/inactive (POST).
     */
    public function setStatus(int $id): RedirectResponse
    {
        $user = $this->userModel->find($id);
        if (! $user) {
            return redirect()->back()->with('error', 'User not found.');
        }

        $status = (int) $this->request->getPost('is_active');
        if (! in_array($status, [0, 1], true)) {
            return redirect()->back()->with('error', 'Invalid status.');
        }

        $this->userModel->update($id, ['is_active' => $status]);
        $this->logActivity('admin', 'user_status', $id, ($status === 1 ? 'Activated' : 'Deactivated') . ' user: ' . ($user['email'] ?? ''));
        $msg = $status === 1 ? 'User marked active.' : 'User marked inactive.';
        return redirect()->back()->with('message', $msg);
    }
}
