<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel Remote Deploy Environments
    |--------------------------------------------------------------------------
    |
    | Here you can configure the available environments that are available when
    | running php artisan deploy:remote. Each of these will create a temp
    | config json file within json for future usage.
    |
    */

    'environments' => [
        'Local',
        'Staging',
        'Production'
    ],

    'tasks' => [
        'deploy' => [
            'git pull origin' => [
                'prompt' => 'Please enter a branch you want to deploy'
            ]
        ]
    ]
];