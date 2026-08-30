<?php

return [
    'common' => [
        'hello' => 'Hello,',
        'expires' => 'This link will expire in :minutes minutes.',
        'fallback' => "If the button above doesn't work, paste this URL into your browser:",
    ],
    'verify' => [
        'html_title' => ':app - Verify Email',
        'subject' => ':app - Verify your email',
        'intro' => 'Please verify your email address for your <strong>:app</strong> account by clicking the button below.',
        'cta' => 'Verify email address',
        'ignore' => "If you didn’t ask to verify this address, you can safely ignore this message.",
        'footer' => "© :year :app — You're receiving this email because an account was created with this address.",
    ],
    'reset' => [
        'html_title' => ':app - Reset Password',
        'subject' => ':app - Reset your password',
        'intro' => 'We received a request to reset the password for your <strong>:app</strong> account. Click the button below to choose a new password.',
        'cta' => 'Reset password',
        'ignore' => 'If you did not request a password reset, you can safely ignore this email - your password will not change.',
        'footer' => "© :year :app — You're receiving this email because a password reset was requested for your account.",
    ],
];
