<?php

namespace App\Models;

use CodeIgniter\Model;

class GamingControllerModel extends Model
{
    protected $table            = 'gaming_controllers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'gaming_category_id', 'is_active'];
}
