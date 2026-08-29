<?php

return [
    'original_url.required' => 'Please provide a URL to shorten.',
    'original_url.max'      => 'The URL is too long (2048 characters max).',
    'original_url.regex'    => 'Please enter a valid Destination URL (e.g. example.com).',
    'title.max'             => 'The title is too long (255 characters max).',
    'short_code.required'   => 'Please provide an alias for this URL.',
    'short_code.max'        => 'The alias is too long (50 characters max).',
    'short_code.alpha_dash' => 'The alias may only contain letters, numbers, dashes, and underscores.',
    'short_code.taken'      => 'This alias is already taken. Please choose another.',
    'original_url.loop'     => 'The destination URL cannot point to its own short link.',
];
