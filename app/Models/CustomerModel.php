<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Phone uniqueness is enforced using {@see phoneComparableKey()} so the same mobile
 * in different formats (e.g. +91… vs leading 0) maps to one customer.
 */
class CustomerModel extends Model
{
    protected $table            = 'customers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'customer_type',
        'name',
        'phone',
        'email',
        'address_line1',
        'address_line2',
        'city',
        'state',
        'postal_code',
        'tax_number',
        'notes',
        'is_active',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';
    protected $dateFormat    = 'datetime';

    /**
     * Digits only; for numbers with 10+ digits, use the last 10 (typical mobile) for comparison.
     */
    public static function phoneComparableKey(?string $phone): string
    {
        $d = preg_replace('/\D+/', '', (string) $phone);
        if ($d === '') {
            return '';
        }
        if (strlen($d) >= 10) {
            return substr($d, -10);
        }

        return $d;
    }

    /**
     * First active customer whose phone matches $phone (same comparable key), or null.
     */
    public function findActiveByPhoneComparable(string $phone): ?array
    {
        $target = self::phoneComparableKey($phone);
        if ($target === '') {
            return null;
        }
        foreach ($this->builder()->select('id, name, phone, email, customer_type, is_active')->where('is_active', 1)->get()->getResultArray() as $row) {
            if (self::phoneComparableKey($row['phone'] ?? '') === $target) {
                return $row;
            }
        }

        return null;
    }

    /**
     * Another customer (id != $exceptCustomerId) with the same phone key, or null.
     * Includes inactive rows so the same number is not stored twice.
     */
    public function findOtherByPhoneComparable(string $phone, ?int $exceptCustomerId = null): ?array
    {
        $target = self::phoneComparableKey($phone);
        if ($target === '') {
            return null;
        }
        $builder = $this->builder()->select('id, name, phone, is_active');
        if ($exceptCustomerId !== null) {
            $builder->where('id !=', (int) $exceptCustomerId);
        }
        foreach ($builder->get()->getResultArray() as $row) {
            if (self::phoneComparableKey($row['phone'] ?? '') === $target) {
                return $row;
            }
        }

        return null;
    }
}
