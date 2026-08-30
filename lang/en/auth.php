<?php

return [
    'failed' => 'These credentials do not match our records.',
    'password' => 'The provided password is incorrect.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',
    'password_mismatch' => 'The provided password does not match your current password.',

    'fields' => [
        'email' => 'Email address',
        'email_placeholder' => 'you@example.com',
        'password' => 'Password',
        'password_confirmation' => 'Confirm password',
        'password_placeholder' => 'At least 8 characters',
        'new_password' => 'New password',
        'confirm_new_password' => 'Confirm new password',
    ],

    'login' => [
        'title' => 'Welcome back',
        'subtitle' => 'Log in to your account to continue',
        'verify_new_email_hint' => 'You opened a link to verify a <strong>new</strong> email address. Sign in with the email address <strong>already on your account</strong> (not the new one).',
        'forgot' => 'Forgot password?',
        'remember' => 'Remember me',
        'submit' => 'Log in',
        'submitting' => 'Logging in...',
        'no_account' => "Don't have an account?",
        'create_one' => 'Create one',
    ],

    'register' => [
        'title' => 'Create an account',
        'subtitle' => 'Start shortening your URLs today',
        'submit' => 'Create account',
        'submitting' => 'Creating the account...',
        'have_account' => 'Already have an account?',
        'login_link' => 'Log in',
    ],

    'forgot' => [
        'title' => 'Forgot your password?',
        'subtitle' => "No problem. Enter your email and we'll send you a reset link.",
        'submit' => 'Send reset link',
        'submitting' => 'Sending...',
        'remembered' => 'Remembered it?',
        'back' => 'Back to log in',
    ],

    'reset' => [
        'title' => 'Set new password',
        'subtitle' => 'Choose a strong password for your account.',
        'submit' => 'Reset password',
    ],

    'verify' => [
        'title' => 'Verify your email',
        'logout' => 'Log out',
        'body' => 'We sent a verification link to your email address. Click it to activate your account.',
        'link_sent' => 'A fresh verification link has been sent.',
        'resend' => 'Resend verification email',
        'resending' => 'Sending...',
        'back_home' => 'Back to home',
        'go_profile' => 'Go to profile',
        'dashboard_block' => 'Please verify your email address to unlock the dashboard features. We sent you a verification link.',
        'expired_title' => 'This verification link no longer works.',
        'expired_body_1' => 'That usually means the link expired or you requested a different email address afterward.',
        'expired_body_2' => 'Go to your profile to resend one, and open the verification link from your most recent email',
    ],
];
