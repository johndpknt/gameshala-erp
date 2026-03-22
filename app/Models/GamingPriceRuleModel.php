<?php

namespace App\Models;

use CodeIgniter\Model;

class GamingPriceRuleModel extends Model
{
    protected $table            = 'gaming_price_rules';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['gaming_category_id', 'gaming_mode_id', 'price_type', 'price', 'is_active'];
}
