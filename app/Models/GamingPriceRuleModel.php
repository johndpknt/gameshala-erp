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

    /**
     * Human labels for price_type (UI + invoices).
     *
     * @return array<string, string>
     */
    public static function priceTypeLabels(): array
    {
        return [
            'PER_MINUTE' => 'Per minute',
            'PER_30_MIN' => 'Per 30 min (ceil)',
            'PER_HOUR'   => 'Per hour (pro-rated)',
            'FIXED'      => 'Fixed (flat)',
            'MIN_15'     => 'Per 15 min block',
            'MIN_25'     => 'Per 25 min block',
            'MIN_30'     => 'Per 30 min block',
            'MIN_45'     => 'Per 45 min block',
            'MIN_60'     => 'Per 60 min block',
        ];
    }

    public static function priceTypeValidationList(): string
    {
        return implode(',', array_keys(self::priceTypeLabels()));
    }

    /**
     * Billable gaming amount from session length (minutes) and rule.
     * Block types: price × number of ceil(minutes / block) slabs.
     */
    public static function computeGamingAmountFromDuration(float $minutes, float $price, string $priceType): float
    {
        $priceType = $priceType !== '' ? $priceType : 'FIXED';
        $m         = max(0.0, $minutes);

        return match ($priceType) {
            'PER_MINUTE' => round($price * $m, 2),
            'PER_30_MIN' => round($price * ceil($m / 30), 2),
            'PER_HOUR'   => round($price * ($m / 60), 2),
            'MIN_15'     => round($price * ($m > 0 ? ceil($m / 15) : 0), 2),
            'MIN_25'     => round($price * ($m > 0 ? ceil($m / 25) : 0), 2),
            'MIN_30'     => round($price * ($m > 0 ? ceil($m / 30) : 0), 2),
            'MIN_45'     => round($price * ($m > 0 ? ceil($m / 45) : 0), 2),
            'MIN_60'     => round($price * ($m > 0 ? ceil($m / 60) : 0), 2),
            default      => round($price, 2),
        };
    }
}
