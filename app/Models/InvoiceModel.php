<?php

namespace App\Models;

use CodeIgniter\Model;

class InvoiceModel extends Model
{
    protected $table            = 'invoices';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'invoice_number',
        'order_id',
        'gaming_visit_id',
        'customer_id',
        'subtotal',
        'discount_amount',
        'tax_amount',
        'total_amount',
        'status',
        'issued_at',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = true;
    protected $createdField   = 'created_at';
    protected $updatedField   = 'updated_at';
    protected $dateFormat    = 'datetime';
}
