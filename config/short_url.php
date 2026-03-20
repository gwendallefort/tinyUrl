<?php

return [
    'reserved_codes' => array_unique(array_merge(
        // Block all single letters (a-z) and single digits (0-9)
        range('a', 'z'),
        array_map('strval', range(0, 9)),
    [
        '-',
        '_',

        // App routes
        'home',
        'dashboard',
        'profile',

        // Fortify auth routes
        'login',
        'logout',
        'register',
        'forgot-password',
        'reset-password',
        'user',

        // Fortify optional features (not currently enabled, reserved for future use)
        'two-factor-challenge',
        'confirm-password',
        'email',

        // Semantic reservations
        'admin',
        'api',
        'app',
        'help',
        'about',
        'support',
        'terms',
        'privacy',
        'contact',
        'status',
        'static',
        'assets',
        'storage',
        'media',

        // Link management features
        'stats',          // per-link analytics
        'analytics',      // global analytics dashboard
        'qr',             // QR code generation
        'preview',        // link preview page
        'tags',           // link tagging/categorization
        'search',         // search across links
        'bulk',           // bulk import/export
        'archive',        // archived links section
        'expired',        // expired links page

        // User/account features
        'settings',       // user settings page
        'notifications',  // notification center
        'onboarding',     // new user onboarding flow
        'verify',         // email/phone verification
        'invite',         // invitation system
        'invitations',    // invitation management
        'tokens',         // API token management
        'api-keys',       // alternative naming for API tokens

        // Team/organization features
        'teams',
        'organizations',
        'members',
        'roles',
        'permissions',

        // Monetization features
        'pricing',
        'plans',
        'upgrade',
        'checkout',
        'billing',
        'subscription',
        'invoices',

        // Technical/infra:
        'webhooks',
        'health',
        'sitemap',
        'feed',
        'robots',
        'well-known',

        // Common short redirect prefixes (collision-prone)
        'go',
        'me',
        'my',
        'to',
        'id',
        'ok',

        // others
        'aB3x9k' // url from the example in the welcome page
    ])),
];
