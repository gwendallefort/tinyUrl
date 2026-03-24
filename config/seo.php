<?php

return [

    'home_description' => env(
        'SEO_HOME_DESCRIPTION',
        'Shorten long URLs into clean, shareable links with instant redirects and click tracking. Create a free account to manage your links.'
    ),

    'default_description' => env(
        'SEO_DEFAULT_DESCRIPTION',
        'Create short links, track clicks, and update destinations anytime.'
    ),

    'auth_description' => env(
        'SEO_AUTH_DESCRIPTION',
        'Sign in or create an account to shorten URLs and manage your links.'
    ),

    /*
    | Open Graph / Twitter image: full URL (https://...) or site-relative path (e.g. images/og.png).
    | Default is public/images/og.png.
    */
    'og_image' => env('SEO_OG_IMAGE', 'images/og.png'),

];
