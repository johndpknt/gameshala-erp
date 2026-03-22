<?php

namespace App\Models;

use CodeIgniter\Model;

class BatchProcurementRuleModel extends Model
{
    protected $table            = 'batch_procurement_rules';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields   = true;
    protected $allowedFields   = [
        'batch_id',
        'procurement_rule_id',
        'applied_at',
        'remarks',
        'is_active',
    ];
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
}
