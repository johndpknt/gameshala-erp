<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderItemModel extends Model
{
    protected $table            = 'order_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'order_id',
        'product_id',
        'stock_batch_id',
        'qty',
        'unit_price',
        'listing_price_snapshot',
        'unit_cost_snapshot',
        'discount_amount',
        'line_total',
        'created_at',
    ];
    protected $useTimestamps   = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = '';
    protected $dateFormat      = 'datetime';
}
