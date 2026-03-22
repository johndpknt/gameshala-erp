<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes    = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name',
        'email',
        'phone',
        'password_hash',
        'role',
        'is_active',
        'last_login_at',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $dateFormat    = 'datetime';

    /** @var list<string> */
    protected $hidden = ['password_hash'];

    /**
     * Find user by email (for login).
     */
    public function findByEmail(string $email): ?array
    {
        $row = $this->where('email', $email)->first();
        return $row ?: null;
    }

    /**
     * Verify password against stored hash.
     */
    public function verifyPassword(string $password, string $passwordHash): bool
    {
        return password_verify($password, $passwordHash);
    }

    /**
     * Update last_login_at for the user.
     */
    public function recordLogin(int $userId): bool
    {
        return $this->update($userId, [
            'last_login_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
