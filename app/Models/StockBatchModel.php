<?php

namespace App\Models;

use CodeIgniter\Model;

class StockBatchModel extends Model
{
    protected $table            = 'stock_batches';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'batch_code',
        'product_id',
        'vendor_id',
        'purchased_qty',
        'remaining_qty',
        'unit_cost',
        'selling_price',
        'received_at',
        'remarks',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';
    protected $dateFormat    = 'datetime';
}
