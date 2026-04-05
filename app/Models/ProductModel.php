<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductModel extends Model
{
    protected $table            = 'products';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'sku',
        'name',
        'slug',
        'description',
        'image_url',
        'unit',
        'is_public',
        'is_active',
        'created_at',
        'updated_at',
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $dateFormat    = 'datetime';

    /**
     * Food & beverage catalog SKUs use batch-level selling_price; procurement rules are optional.
     */
    public static function skuIsFoodOrBeverage(?string $sku): bool
    {
        if ($sku === null || $sku === '') {
            return false;
        }
        $u = strtoupper($sku);

        return str_starts_with($u, 'BEVE-') || str_starts_with($u, 'FOOD-');
    }

    /**
     * Convert pipe-separated image_url from DB to array of URLs.
     */
    public static function imageUrlToArray(?string $imageUrl): array
    {
        if ($imageUrl === null || trim($imageUrl) === '') {
            return [];
        }
        return array_values(array_filter(array_map('trim', explode('|', $imageUrl))));
    }
}
