<?php

namespace App\Models;

use CodeIgniter\Model;

class GamingCategoryModel extends Model
{
    protected $table            = 'gaming_categories';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'is_active'];
}
