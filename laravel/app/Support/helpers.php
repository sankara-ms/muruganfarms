<?php

use App\Support\Site;

if (! function_exists('site')) {
    /**
     * The active client/site resolved from config/site.php.
     */
    function site(): Site
    {
        return app(Site::class);
    }
}

if (! function_exists('site_view')) {
    /**
     * Render a client view, falling back to the default client.
     */
    function site_view(string $name, array $data = [])
    {
        return view(site()->viewName($name), $data);
    }
}
