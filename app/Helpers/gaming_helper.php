<?php

if (! function_exists('gaming_time_duration_label')) {
    /**
     * Human-readable label for gaming_price_rules.price_type (and related UI).
     */
    function gaming_time_duration_label($type)
    {
        $map = [
            'PER_MINUTE' => 'Per minute',
            'PER_30_MIN' => 'Per 30 min',
            'PER_HOUR'   => 'Per hour',
            'FIXED'      => 'Fixed',
            'MIN_15'     => 'Per 15 min block',
            'MIN_25'     => 'Per 25 min block',
            'MIN_30'     => 'Per 30 min block',
            'MIN_45'     => 'Per 45 min block',
            'MIN_60'     => 'Per 60 min block',
        ];

        $key = strtoupper(trim($type ?? ''));

        return $map[$key] ?? '—';
    }
}
