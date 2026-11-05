<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Applies the visitor's chosen language for the active client.
 * A manual switch wins forever; until then the browser's own language
 * decides, so a Tamil device opens the Tamil site and everyone else
 * gets the client's default locale.
 */
class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $available = site()->locales();
        $chosen = $request->session()->get('locale_choice');

        $locale = is_string($chosen) && in_array($chosen, $available, true)
            ? $chosen
            : $this->negotiate($request, $available);

        app()->setLocale($locale);
        $request->session()->put('locale', $locale);

        return $next($request);
    }

    /** First entry of the browser's preference list that the client offers. */
    private function negotiate(Request $request, array $available): string
    {
        foreach ($request->getLanguages() as $wanted) {
            $base = strtolower(strtok($wanted, '-_'));
            foreach ($available as $option) {
                if (strtolower($option) === $base) {
                    return $option;
                }
            }
        }

        $default = (string) site()->config('default_locale', 'en');

        return in_array($default, $available, true) ? $default : $available[0];
    }
}
