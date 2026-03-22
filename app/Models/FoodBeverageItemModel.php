<?php

namespace App\Models;

use CodeIgniter\Model;

class FoodBeverageItemModel extends Model
{
    protected $table            = 'food_beverage_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'unit_label', 'price', 'is_active'];
}
