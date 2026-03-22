<?php

/**
 * Asset URL using the current request's scheme (http/https).
 * Use for CSS, JS, images so they load over the same protocol as the page and avoid mixed content.
 */
if (! function_exists('asset_url')) {
    function asset_url(string $path): string
    {
        $request = service('request');
        $scheme  = $request->getUri()->getScheme();

        return base_url($path, $scheme);
    }
}
