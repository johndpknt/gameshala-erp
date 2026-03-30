<?php

namespace App\Models;

use CodeIgniter\Model;

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
     * Match by last 10 digits (handles +91, spaces, dashes). Returns first row if found.
     */
    public function findByPhoneDigits(string $phone): ?array
    {
        $digits = preg_replace('/\D+/', '', $phone);
        if ($digits === '') {
            return null;
        }
        $last10 = strlen($digits) >= 10 ? substr($digits, -10) : $digits;
        $prefix = $this->db->DBPrefix;
        $table  = $this->table;

        $row = $this->db->query(
            "SELECT * FROM `{$prefix}{$table}` WHERE RIGHT(REGEXP_REPLACE(COALESCE(`phone`, ''), '[^0-9]', ''), 10) = ? LIMIT 1",
            [$last10]
        )->getRowArray();

        return $row ?: null;
    }
}
