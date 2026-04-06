<?php

if (! function_exists('is_admin')) {
    /**
     * Logged-in user has ADMIN role (full access).
     */
    function is_admin(): bool
    {
        return session()->get('user_role') === 'ADMIN';
    }
}

if (! function_exists('is_staff')) {
    /**
     * Logged-in user has STAFF role (limited UI).
     */
    function is_staff(): bool
    {
        return session()->get('user_role') === 'STAFF';
    }
}
