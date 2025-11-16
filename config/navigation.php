<?php
return [
    [
        'label' => 'Dashboard',
        'icon' => 'fas fa-tachometer-alt',
        'route' => 'dashboard',
    ],
    [
        'label' => 'Gestion de Documents',
        'icon' => 'fas fa-folder',
        'children' => [
            [ 'label' => 'Mes Documents', 'route' => 'documents.index' ],
            [ 'label' => 'Ajout', 'route' => 'documents.create' ],
        ],
    ],
    [
        'label' => 'Historique',
        'icon' => 'fas fa-history',
        'route' => 'historique',
    ],
    [
        'label' => 'Utilisateurs',
        'icon' => 'fas fa-users',
        'route' => 'users.index',
    ],
    [
        'label' => 'Profil',
        'icon' => 'fas fa-user',
        'route' => 'profile',
    ],
    [
        'label' => 'Administration',
        'icon' => 'fas fa-shield-alt',
        'children' => [
            [ 'label' => 'Documents en Attente', 'route' => 'documents.pending' ],
            [ 'label' => 'Agents', 'route' => 'agents.index' ],
            [ 'label' => 'Types de Documents', 'route' => 'document-types.index' ],
            [ 'label' => 'Directions', 'route' => 'directions.index' ],
            [ 'label' => 'Services', 'route' => 'services.index' ],
            [ 'label' => 'Paramètres', 'route' => 'admin.index' ],
        ],
    ],
];
