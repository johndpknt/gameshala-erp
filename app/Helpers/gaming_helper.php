<?php

/** Human-readable label for gaming_price_rules.price_type (time duration). */
function gaming_time_duration_label(string $priceType): string
{
    $map = [
        'MIN_15'     => '15 min',
        'MIN_25'     => '25 min',
        'MIN_45'     => '45 min',
        'MIN_60'     => '60 min',
        'PER_MINUTE' => '15 min',
        'PER_30_MIN' => '25 min',
        'PER_HOUR'   => '60 min',
        'FIXED'      => '45 min',
    ];

    return $map[$priceType] ?? $priceType;
}
