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
}
