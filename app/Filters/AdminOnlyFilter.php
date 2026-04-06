<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Allow only users with role ADMIN (e.g. vendors, stock batches, procurement rules).
 */
class AdminOnlyFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        if (! session()->has('user_id')) {
            return redirect()->to(site_url('login'))->with('error', 'Please log in.');
        }
        if (session()->get('user_role') !== 'ADMIN') {
            return redirect()->to(site_url('/'))->with('error', 'You do not have access to this page.');
        }

        return null;
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
