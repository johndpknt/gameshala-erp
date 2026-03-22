<?php

namespace App\Models;

use CodeIgniter\Model;

class VendorModel extends Model
{
    protected $table            = 'vendors';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields     = true;
    protected $allowedFields    = [
        'name',
        'contact_person',
        'phone',
        'email',
        'remarks',
        'is_active',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';
    protected $dateFormat     = 'datetime';
}
