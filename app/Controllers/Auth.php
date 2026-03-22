<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\HTTP\RedirectResponse;

class Auth extends BaseController
{
    protected UserModel $userModel;

    public function __construct()
    {
        $this->userModel = model(UserModel::class);
    }

    /**
     * Show login form.
     *
     * @return string|RedirectResponse
     */
    public function login()
    {
        if ($this->isLoggedIn()) {
            return redirect()->to('/');
        }

        helper('form');
        $data = [
            'pageTitle' => 'Login - Gameshala ERP',
            'error'     => session('login_error'),
        ];
        session()->remove('login_error');

        return view('layout/auth', [
            'pageTitle' => $data['pageTitle'],
            'content'   => view('auth/login', $data),
        ]);
    }

    /**
     * Process login (POST).
     */
    public function attemptLogin(): RedirectResponse
    {
        $email    = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        if (! $email || ! $password) {
            return $this->loginFailed('Email and password are required.');
        }

        $user = $this->userModel->findByEmail($email);
        if (! $user) {
            return $this->loginFailed('Invalid email or password.');
        }

        if ((int) ($user['is_active'] ?? 0) !== 1) {
            return $this->loginFailed('This account is inactive. Contact an administrator.');
        }

        if (! $this->userModel->verifyPassword($password, $user['password_hash'])) {
            return $this->loginFailed('Invalid email or password.');
        }

        $this->userModel->recordLogin((int) $user['id']);

        session()->set([
            'user_id'   => $user['id'],
            'user_name' => $user['name'],
            'user_email'=> $user['email'],
            'user_role' => $user['role'],
        ]);

        $this->logActivity('auth', 'login', (int) $user['id'], 'Logged in');
        return redirect()->to('/')->with('message', 'Welcome back, ' . $user['name']);
    }

    /**
     * Log out and redirect to login.
     */
    public function logout(): RedirectResponse
    {
        $this->logActivity('auth', 'logout', session('user_id'), 'Logged out');
        session()->destroy();
        return redirect()->to('login')->with('message', 'You have been logged out.');
    }

    protected function loginFailed(string $message): RedirectResponse
    {
        session()->setFlashdata('login_error', $message);
        return redirect()->back()->withInput();
    }

    protected function isLoggedIn(): bool
    {
        return session()->has('user_id');
    }
}
