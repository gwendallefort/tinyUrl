<?php

return [
    'title' => 'Tableau de bord',
    'new_url' => 'Nouveau lien court',
    'flash' => [
        'email_verified' => 'Votre adresse e-mail a été vérifiée avec succès.',
        'created' => 'Lien court créé !',
        'updated' => 'Lien court mis à jour.',
        'deleted' => 'Lien court supprimé.',
    ],
    'search' => [
        'label' => 'Rechercher des liens courts',
        'placeholder' => 'Rechercher...',
        'clear' => 'Effacer',
    ],
    'empty' => [
        'search_title' => 'Aucun lien court correspondant',
        'search_body' => 'Aucune correspondance pour « :search » dans les alias ou les URL d’origine.',
        'clear_search' => 'Effacer la recherche',
        'title' => 'Aucun lien court pour l’instant',
        'body' => 'Vos URL raccourcies apparaîtront ici une fois créées.',
        'cta' => 'Créer votre premier lien court',
    ],
    'table' => [
        'url' => 'URL',
        'original' => 'Origine',
        'clicks' => 'Clics',
        'actions' => 'Actions',
    ],
    'actions' => [
        'copy_title' => 'Copier le lien court',
        'edit' => 'Modifier',
        'qr' => 'Code QR',
        'delete' => 'Supprimer',
    ],
    'fields' => [
        'title' => 'Titre',
        'optional' => '(facultatif)',
        'title_placeholder' => 'Mon super lien',
        'destination' => 'URL de destination',
        'destination_placeholder' => 'exemple.com/url-tres-longue',
        'custom_alias' => 'Alias personnalisé',
        'alias_optional_hint' => '(facultatif — généré automatiquement si vide)',
        'alias_placeholder' => 'mon-alias',
        'alias_case_hint' => 'Les alias sont sensibles à la casse — ABC et abc sont des liens différents.',
        'alias' => 'Alias',
    ],
    'suggest' => [
        'label' => 'Suggérer',
        'loading' => 'Suggestion…',
        'missing_context' => 'Saisissez d’abord une URL de destination ou un titre.',
        'empty' => 'Aucune suggestion disponible. Essayez un titre ou une URL plus clairs.',
        'unavailable' => 'Impossible de suggérer des alias pour le moment. Réessayez.',
    ],
    'create' => [
        'title' => 'Nouveau lien court',
        'submit' => 'Créer le lien court',
    ],
    'edit' => [
        'title' => 'Modifier le lien court',
        'submit' => 'Enregistrer',
    ],
    'delete' => [
        'title' => 'Supprimer le lien court ?',
        'intro' => 'Vous êtes sur le point de supprimer :',
        'irreversible' => 'Cette action est irréversible.',
        'submit' => 'Supprimer',
    ],
    'qr' => [
        'title' => 'Code QR',
        'scan' => 'Scanner pour ouvrir',
        'alt' => 'Code QR du lien court',
        'download' => 'Télécharger le PNG',
    ],
];
