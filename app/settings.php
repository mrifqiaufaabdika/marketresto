<?php
return [
    'settings' => [
        // comment this line when deploy to production environment
        'displayErrorDetails' => APP_DEBUG_MODE,
        'upload_directory' => __DIR__ .'/../public/upload',
        
        // View settings        
        'view' => [
            'template_path' => __DIR__ . '/templates',
            'twig' => [
                'cache' => __DIR__ . '/../storage/cache/twig',
                'debug' => APP_DEBUG_MODE,
                'auto_reload' => true,
            ],
        ],

        // Database Settings
        'db' => [
            'host' => '127.0.0.1',
            'user' => 'root',
            'pass' => '',
            'dbname' => 'market_resto',
            'driver' => 'mysql'
        ],

        // monolog settings
        'logger' => [
            'name' => APP_NAME,
            'path' => __DIR__ . '/../storage/logs/app.log',
        ],
    ],
];
