<?php

namespace App\Models;

use CodeIgniter\Model;

class CouponModel extends Model
{
    protected $table            = 'coupons';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'code',
        'discount_type',
        'discount_value',
        'min_order_amount',
        'max_discount_amount',
        'usage_limit',
        'used_count',
        'valid_from',
        'valid_to',
        'is_active',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';
    protected $dateFormat    = 'datetime';

    /**
     * Find coupon by code (trim + case-insensitive on stored code).
     */
    public function findByCode(string $code): ?array
    {
        $t = strtolower(trim($code));
        if ($t === '') {
            return null;
        }
        $table = $this->db->DBPrefix . $this->table;
        $row   = $this->db->query(
            'SELECT * FROM ' . $table . ' WHERE LOWER(TRIM(`code`)) = ? LIMIT 1',
            [$t]
        )->getRowArray();

        return $row ?: null;
    }
}
