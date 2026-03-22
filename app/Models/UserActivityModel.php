<?php

namespace App\Models;

use CodeIgniter\Model;

class UserActivityModel extends Model
{
    protected $table            = 'user_activities';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'module',
        'action',
        'entity_id',
        'description',
        'created_at',
    ];
    protected $useTimestamps   = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = '';
    protected $dateFormat      = 'datetime';
}
