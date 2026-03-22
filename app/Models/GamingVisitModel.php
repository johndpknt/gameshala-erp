<?php

namespace App\Models;

use CodeIgniter\Model;

class GamingVisitModel extends Model
{
    protected $table            = 'gaming_visits';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'customer_id',
        'gaming_price_rule_id',
        'invoice_id',
        'no_of_players',
        'start_time',
        'end_time',
        'gaming_amount',
        'food_amount',
        'total_amount',
        'status',
    ];
}
