<?php

namespace App\Models;

use CodeIgniter\Model;

class GamingTitleModel extends Model
{
    protected $table            = 'gaming_titles';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'gaming_controller_id', 'is_active'];
}
