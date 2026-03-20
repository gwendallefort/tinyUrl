<?php

return [
    'reserved_codes' => array_unique(array_merge(
        // Block all single letters (a-z) and single digits (0-9)
        range('a', 'z'),
        array_map('strval', range(0, 9)),
        // Block all HTTP status code numbers (100-599)
        array_map('strval', range(100, 599)),
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
        'signin',
        'signup',
        'auth',
        'forgot-password',
        'reset-password',
        'password',
        'passwords',
        'user',
        'account',
        'two-factor-challenge',
        'confirm-password',
        'email',

        // Semantic reservations
        'admin',
        'moderation',
        'reports',
        'abuse',
        'ban',
        'banned',
        'audit',
        'logs',
        'api',
        'app',
        'help',
        'about',
        'faq',
        'blog',
        'careers',
        'press',
        'legal',
        'cookies',
        'support',
        'terms',
        'privacy',
        'contact',
        'status',
        'static',
        'assets',
        'storage',
        'media',
        'users',
        'team',
        'org',
        'features',
        'subscriptions',
        'keys',
        'metrics',
        'xmlrpc',

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
        'token',
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
        'sanctum',
        'horizon',
        'telescope',
        'nova',
        'pulse',
        'webhooks',
        'health',
        'docs',
        'openapi',
        'swagger',
        'graphql',
        'v1',
        'v2',
        'v3',
        'v4',
        'v5',
        'v6',
        'v7',
        'v8',
        'v9',
        'v10',
        'sitemap',
        'feed',
        'robots',
        'well-known',
        'wp-admin',
        'wp-login',
        'phpmyadmin',
        'server-status',
        'cgi-bin',

        // Common short redirect prefixes (collision-prone)
        'go',
        'me',
        'my',
        'to',
        'id',
        'ok',

        // Others
        'aB3x9k' // url from the example in the welcome page
    ])),
];
