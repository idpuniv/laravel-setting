<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Configuration des champs pour l'interface
    |--------------------------------------------------------------------------
    | Chaque champ définit son type, label, description et sa valeur par défaut.
    | C'est la source unique de vérité pour les valeurs par défaut.
    */

    'fields' => [

        /*
        |----------------------------------------------------------------------
        | SYSTEM (admin uniquement - permission: edit-system-settings)
        |----------------------------------------------------------------------
        */

        'system.maintenance_mode' => [
            'type' => 'boolean',
            'label' => 'Mode maintenance',
            'description' => 'Activer le mode maintenance pour bloquer l\'accès au site.',
            'default' => false,
        ],

        'system.maintenance_message' => [
            'type' => 'text',
            'label' => 'Message de maintenance',
            'description' => 'Message affiché aux utilisateurs pendant la maintenance.',
            'default' => 'Site en maintenance',
        ],

        'system.security.password_min_length' => [
            'type' => 'number',
            'label' => 'Longueur minimale du mot de passe',
            'description' => 'Nombre minimum de caractères requis pour les mots de passe.',
            'min' => 6,
            'max' => 20,
            'default' => 8,
        ],

        'system.security.password_require_special' => [
            'type' => 'boolean',
            'label' => 'Caractère spécial obligatoire',
            'description' => 'Exiger au moins un caractère spécial dans les mots de passe.',
            'default' => true,
        ],

        'system.security.max_login_attempts' => [
            'type' => 'number',
            'label' => 'Tentatives maximum',
            'description' => 'Nombre maximum de tentatives de connexion avant blocage.',
            'min' => 1,
            'max' => 10,
            'default' => 5,
        ],

        'system.registration_enabled' => [
            'type' => 'boolean',
            'label' => 'Inscriptions autorisées',
            'description' => 'Permettre aux nouveaux utilisateurs de créer un compte.',
            'default' => true,
        ],

        'system.session_lifetime' => [
            'type' => 'number',
            'label' => 'Durée de session',
            'description' => 'Durée de vie d\'une session utilisateur (en minutes).',
            'min' => 60,
            'max' => 1440,
            'default' => 120,
        ],

        /*
        |----------------------------------------------------------------------
        | USER (préférences utilisateur - accessible à tous)
        |----------------------------------------------------------------------
        */

        'user.theme' => [
            'type' => 'select',
            'label' => 'Thème',
            'description' => 'Choisissez le thème de l\'interface.',
            'options' => [
                'light' => 'Clair',
                'dark' => 'Sombre',
            ],
            'default' => 'light',
        ],

        'user.notifications.email' => [
            'type' => 'boolean',
            'label' => 'Notifications email',
            'description' => 'Recevoir les notifications par email.',
            'default' => true,
        ],

        'user.notifications.sms' => [
            'type' => 'boolean',
            'label' => 'Notifications SMS',
            'description' => 'Recevoir les notifications par SMS.',
            'default' => false,
        ],

        'user.notifications.push' => [
            'type' => 'boolean',
            'label' => 'Notifications push',
            'description' => 'Recevoir les notifications push.',
            'default' => true,
        ],

        'user.default_language' => [
            'type' => 'select',
            'label' => 'Langue',
            'description' => 'Langue utilisée par défaut dans l\'application.',
            'options' => [
                'fr' => 'Français',
                'en' => 'English',
                'es' => 'Español',
                'de' => 'Deutsch',
            ],
            'default' => 'fr',
        ],

        'user.timezone' => [
            'type' => 'select',
            'label' => 'Fuseau horaire',
            'description' => 'Fuseau horaire utilisé pour afficher les dates.',
            'options' => [
                'Europe/Paris' => '🇫🇷 Paris',
                'Europe/London' => '🇬🇧 Londres',
                'Europe/Berlin' => '🇩🇪 Berlin',
                'America/New_York' => '🇺🇸 New York',
            ],
            'default' => 'Europe/Paris',
        ],

        'user.items_per_page' => [
            'type' => 'select',
            'label' => 'Éléments par page',
            'description' => 'Nombre d\'éléments affichés dans les listes.',
            'options' => [
                10 => '10 éléments',
                20 => '20 éléments',
                50 => '50 éléments',
                100 => '100 éléments',
            ],
            'default' => 20,
        ],

        'user.show_tutorial' => [
            'type' => 'boolean',
            'label' => 'Afficher le tutoriel',
            'description' => 'Afficher le tutoriel lors de la première connexion.',
            'default' => true,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Organisation des paramètres dans l'interface
    |--------------------------------------------------------------------------
    | Les groupes définissent comment les champs sont organisés dans l'UI.
    | Chaque groupe référence les clés définies dans 'fields'.
    */

    'groups' => [

    'system' => [
        'label' => 'Système',
        'icon' => 'bi bi-gear-fill',
        'description' => 'Paramètres globaux de l\'application',
        'fields' => [
            'system.maintenance_mode',
            'system.maintenance_message',
            'system.security.password_min_length',
            'system.security.password_require_special',
            'system.security.max_login_attempts',
            'system.registration_enabled',
            'system.session_lifetime',
        ],
    ],

    'apparence' => [
        'label' => 'Apparence',
        'icon' => 'bi bi-palette-fill',
        'description' => 'Personnalisez l\'affichage',
        'fields' => [
            'user.theme',
            'user.items_per_page',
            'user.show_tutorial',
        ],
    ],

    'notifications' => [
        'label' => 'Notifications',
        'icon' => 'bi bi-bell-fill',
        'description' => 'Gérez vos préférences de notifications',
        'fields' => [
            'user.notifications.email',
            'user.notifications.sms',
            'user.notifications.push',
        ],
    ],

    'localisation' => [
        'label' => 'Localisation',
        'icon' => 'bi bi-globe-americas',
        'description' => 'Paramètres régionaux',
        'fields' => [
            'user.default_language',
            'user.timezone',
        ],
    ],

],

];