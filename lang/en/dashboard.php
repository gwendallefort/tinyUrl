<?php

return [
    'title' => 'Dashboard',
    'new_url' => 'New short URL',
    'flash' => [
        'email_verified' => 'Your email address has been verified successfully.',
        'created' => 'Short URL created!',
        'updated' => 'Short URL updated successfully.',
        'deleted' => 'Short URL deleted.',
    ],
    'search' => [
        'label' => 'Search short URLs',
        'placeholder' => 'Search...',
        'clear' => 'Clear',
    ],
    'empty' => [
        'search_title' => 'No matching short URLs',
        'search_body' => 'Nothing matched “:search” in short codes or original URLs.',
        'clear_search' => 'Clear search',
        'title' => 'No short URLs yet',
        'body' => 'Your shortened URLs will appear here once you create them.',
        'cta' => 'Create your first short URL',
    ],
    'table' => [
        'url' => 'URL',
        'original' => 'Original',
        'clicks' => 'Clicks',
        'actions' => 'Actions',
    ],
    'actions' => [
        'copy_title' => 'Copy short URL',
        'edit' => 'Edit',
        'qr' => 'QR code',
        'delete' => 'Delete',
    ],
    'fields' => [
        'title' => 'Title',
        'optional' => '(optional)',
        'title_placeholder' => 'My awesome link',
        'destination' => 'Destination URL',
        'destination_placeholder' => 'example.com/very-long-url',
        'custom_alias' => 'Custom alias',
        'alias_optional_hint' => '(optional - auto-generated if empty)',
        'alias_placeholder' => 'my-alias',
        'alias_case_hint' => 'Aliases are case-sensitive - ABC and abc are different links.',
        'alias' => 'Alias',
    ],
    'suggest' => [
        'label' => 'Suggest',
        'loading' => 'Suggesting…',
        'missing_context' => 'Enter a destination URL or title first.',
        'empty' => 'No available suggestions were returned. Try a clearer title or URL.',
        'unavailable' => 'Unable to suggest aliases right now. Please try again.',
    ],
    'create' => [
        'title' => 'New short URL',
        'submit' => 'Create short URL',
    ],
    'edit' => [
        'title' => 'Edit short URL',
        'submit' => 'Save changes',
    ],
    'delete' => [
        'title' => 'Delete short URL?',
        'intro' => 'You are about to delete:',
        'irreversible' => 'This cannot be undone.',
        'submit' => 'Delete',
    ],
    'qr' => [
        'title' => 'QR code',
        'scan' => 'Scan to open',
        'alt' => 'QR code for short URL',
        'download' => 'Download PNG',
    ],
];
