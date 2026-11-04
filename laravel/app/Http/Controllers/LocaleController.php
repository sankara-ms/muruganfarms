<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LocaleController extends Controller
{
    public function __invoke(Request $request, string $locale): RedirectResponse
    {
        if (in_array($locale, site()->locales(), true)) {
            $request->session()->put('locale', $locale);
        }

        return redirect()->to(url()->previous() ?: route('home'));
    }
}
