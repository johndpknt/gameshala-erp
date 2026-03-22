<?php

namespace App\Models;

use CodeIgniter\Model;

class GamingModeModel extends Model
{
    protected $table            = 'gaming_modes';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['name', 'is_active'];
}
