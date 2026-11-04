<?php

/*
|--------------------------------------------------------------------------
| Site registry  (same flow as cms-bus: OPERATORCODE -> Config\Operators)
|--------------------------------------------------------------------------
| One application, many clients. `active` picks the client from SITE_KEY.
| Each client owns its views (sites/{client}/{template,home}), its own
| stylesheet/script, and its own catalogue + contact details.
| Anything a client does not define falls back to the `default` client.
*/

return [

    'active' => env('SITE_KEY', 'murugan'),
    'fallback' => 'default',

    'sites' => [

        'murugan' => [
            'name' => 'Murugan Farms',
            'template' => 'murugan.template',
            'home' => 'murugan.home',
            'locales' => ['en', 'ta'],
            'default_locale' => 'en',
            'stylesheet' => 'sites/murugan/css/site.css',
            'script' => 'sites/murugan/js/site.js',
            'theme_color' => '#17573a',

            'contact' => [
                'phone' => '+919487408709',
                'phone_display' => '+91 94874 08709',
                'whatsapp' => 'https://wa.me/919487408709',
                'email' => 'sankarnaece@gmail.com',
                'map' => null,
            ],

            // image + translation key only; the copy lives in lang/{locale}/site.php
            'catalogue' => [
                ['key' => 'rice', 'image' => 'assets/images/products/rice.jpg'],
                ['key' => 'groundnut', 'image' => 'assets/images/products/groundnut.jpg'],
                ['key' => 'sugarcane', 'image' => 'assets/images/products/sugarcane.jpg'],
                ['key' => 'millets', 'image' => 'assets/images/products/millets.jpg'],
                ['key' => 'coconut', 'image' => 'assets/images/products/coconut.jpg'],
                ['key' => 'banana', 'image' => 'assets/images/products/banana.jpg'],
                ['key' => 'vegetables', 'image' => 'assets/images/products/vegetables.jpg'],
                ['key' => 'turmeric', 'image' => 'assets/images/products/turmeric-jaggery.jpg'],
                ['key' => 'fruits', 'image' => 'assets/images/products/fruits.jpg'],
                ['key' => 'greens', 'image' => 'assets/images/products/greens.jpg'],
            ],

            'enquiry_extras' => ['pulses', 'oilseeds', 'flowers', 'custom'],

            'gallery' => [
                ['key' => 'sunrise', 'image' => 'assets/images/gallery/farm-sunrise.jpg'],
                ['key' => 'tomato', 'image' => 'assets/images/gallery/tomato-vines.jpg'],
                ['key' => 'coconut', 'image' => 'assets/images/gallery/coconut-grove.jpg'],
                ['key' => 'mountain', 'image' => 'assets/images/gallery/mountain-field.jpg'],
                ['key' => 'vegetables', 'image' => 'assets/images/gallery/vegetable-rows.jpg'],
                ['key' => 'banana', 'image' => 'assets/images/gallery/banana-grove.jpg'],
            ],
        ],

        'default' => [
            'name' => 'Murugan Farms',
            'template' => 'default.template',
            'home' => 'default.home',
            'locales' => ['en'],
            'default_locale' => 'en',
            'stylesheet' => null,
            'script' => null,
            'theme_color' => '#17573a',
            'contact' => [
                'phone' => '+919487408709',
                'phone_display' => '+91 94874 08709',
                'whatsapp' => 'https://wa.me/919487408709',
                'email' => 'sankarnaece@gmail.com',
                'map' => null,
            ],
            'catalogue' => [],
            'enquiry_extras' => [],
            'gallery' => [],
        ],

    ],

];
