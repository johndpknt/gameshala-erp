<?php

namespace App\Models;

use CodeIgniter\Model;

class StockMovementModel extends Model
{
    protected $table            = 'stock_movements';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'product_id',
        'batch_id',
        'movement_type',
        'qty_in',
        'qty_out',
        'reference_type',
        'reference_id',
        'note',
        'created_at',
    ];
    protected $useTimestamps   = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = '';
    protected $dateFormat      = 'datetime';
}
