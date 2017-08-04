<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Laravel Remote Deploy Environments
    |--------------------------------------------------------------------------
    |
    | Here you can configure the available environments that are available when
    | running php artisan remote:tasks. Each of these will create a temp
    | config json file within json for future usage.
    |
    */

    'servers' => [
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