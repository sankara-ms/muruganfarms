<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Applies the visitor's chosen language for the active client.
 * The first visit uses the client's default locale (English).
 */
class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $available = site()->locales();
        $locale = $request->session()->get('locale');

        if (! is_string($locale) || ! in_array($locale, $available, true)) {
            $locale = (string) site()->config('default_locale', 'en');
            if (! in_array($locale, $available, true)) {
                $locale = $available[0];
            }
        }

        app()->setLocale($locale);
        $request->session()->put('locale', $locale);

        return $next($request);
    }
}
