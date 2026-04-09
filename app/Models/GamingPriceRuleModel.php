<?php

namespace App\Models;

use CodeIgniter\Model;

class GamingPriceRuleModel extends Model
{
    /** Minutes of grace after each block before the next block price applies. */
    public const BLOCK_BUFFER_MINUTES = 5;

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
        $b = self::BLOCK_BUFFER_MINUTES;

        return [
            'PER_MINUTE' => 'Per minute',
            'PER_30_MIN' => 'Per 30 min (ceil)',
            'PER_HOUR'   => 'Per hour (pro-rated)',
            'FIXED'      => 'Fixed (flat)',
            'MIN_15'     => "Per 15 min block (+{$b} min buffer)",
            'MIN_25'     => "Per 25 min block (+{$b} min buffer)",
            'MIN_30'     => "Per 30 min block (+{$b} min buffer)",
            'MIN_45'     => "Per 45 min block (+{$b} min buffer)",
            'MIN_60'     => "Per 60 min block (+{$b} min buffer)",
            'HALF_DAY'   => 'Half day (6 hrs) — flat price',
            'FULL_DAY'   => 'Full day (11 hrs) — flat price',
        ];
    }

    public static function priceTypeValidationList(): string
    {
        return implode(',', array_keys(self::priceTypeLabels()));
    }

    /**
     * Billable gaming amount from session length (minutes) and rule.
     *
     * Block types (MIN_15 … MIN_60): each slab is (block minutes + BLOCK_BUFFER_MINUTES)
     * of session time for one unit of price. Any positive use pays at least one slab
     * (e.g. 1 min on a 15-block rule still pays one block). Billable minutes use
     * floor() so sub-minute clock noise does not add an extra slab.
     */
    public static function computeGamingAmountFromDuration(float $minutes, float $price, string $priceType): float
    {
        $priceType = $priceType !== '' ? $priceType : 'FIXED';
        $m         = max(0.0, $minutes);

        return match ($priceType) {
            'PER_MINUTE' => round($price * $m, 2),
            'PER_30_MIN' => round($price * ceil($m / 30), 2),
            'PER_HOUR'   => round($price * ($m / 60), 2),
            'MIN_15'     => self::computeBlockAmountWithBuffer($m, $price, 15),
            'MIN_25'     => self::computeBlockAmountWithBuffer($m, $price, 25),
            'MIN_30'     => self::computeBlockAmountWithBuffer($m, $price, 30),
            'MIN_45'     => self::computeBlockAmountWithBuffer($m, $price, 45),
            'MIN_60'     => self::computeBlockAmountWithBuffer($m, $price, 60),
            'HALF_DAY', 'FULL_DAY', 'FIXED' => round($price, 2),
            default      => round($price, 2),
        };
    }

    /**
     * @param float $minutes Raw session length in minutes (typically from timestamps).
     */
    private static function computeBlockAmountWithBuffer(float $minutes, float $price, int $blockMinutes): float
    {
        $raw = max(0.0, $minutes);
        if ($raw <= 0) {
            return 0.0;
        }
        // Whole minutes for slab math; any positive session bills at least one minute (min one slab).
        $m = max(1, (int) floor($raw));
        $period = $blockMinutes + self::BLOCK_BUFFER_MINUTES;
        $slabs  = (int) ceil($m / $period);

        return round($price * $slabs, 2);
    }
}
