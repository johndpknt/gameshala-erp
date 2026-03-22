<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table            = 'orders';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'order_number',
        'customer_id',
        'status',
        'subtotal',
        'discount_amount',
        'tax_amount',
        'total_amount',
        'coupon_id',
        'shipping_name',
        'shipping_phone',
        'shipping_address',
        'created_via',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';
    protected $dateFormat    = 'datetime';
}
