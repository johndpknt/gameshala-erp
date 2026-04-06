<?php

namespace App\Models;

use CodeIgniter\Model;

class GamingVisitFoodItemModel extends Model
{
    protected $table            = 'gaming_visit_food_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['gaming_visit_id', 'food_beverage_item_id', 'product_id', 'quantity', 'line_total'];
}
