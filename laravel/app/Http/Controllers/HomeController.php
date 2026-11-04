<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    public function __invoke()
    {
        return site_view('home', [
            'layout' => site()->viewName('template'),
            'catalogue' => (array) site()->config('catalogue', []),
            'gallery' => (array) site()->config('gallery', []),
            'enquiryExtras' => (array) site()->config('enquiry_extras', []),
        ]);
    }
}
