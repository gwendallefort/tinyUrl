<?php

return [
    'original_url.required' => 'Veuillez indiquer une URL à raccourcir.',
    'original_url.max' => 'L’URL est trop longue (2048 caractères maximum).',
    'original_url.regex' => 'Veuillez saisir une URL de destination valide (ex. exemple.com).',
    'title.max' => 'Le titre est trop long (255 caractères maximum).',
    'short_code.required' => 'Veuillez indiquer un alias pour cette URL.',
    'short_code.max' => 'L’alias est trop long (50 caractères maximum).',
    'short_code.alpha_dash' => 'L’alias ne peut contenir que des lettres, chiffres, tirets et underscores.',
    'short_code.taken' => 'Cet alias est déjà pris. Veuillez en choisir un autre.',
    'original_url.loop' => 'L’URL de destination ne peut pas pointer vers son propre lien court.',
    'rate_limit' => 'Vous ne pouvez créer que :max liens courts sur une période de 24 heures.',
    'suggest' => [
        'unavailable' => 'Impossible de suggérer des alias pour le moment. Réessayez.',
        'missing_context' => 'Indiquez une URL de destination ou un titre pour suggérer des alias.',
    ],
];
