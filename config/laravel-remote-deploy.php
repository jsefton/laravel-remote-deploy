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
        'setup' => [
            'directory' => '/',
            'commands' => [
                "ssh-keygen -f ~/.ssh/id_rsa -t rsa -N '' " => [
                    'confirm' => 'Do you want to create an ssh key'
                ],
                'cat ~/.ssh/id_rsa.pub',
                'cd /var/www/html',
                'git clone' => [
                    'confirm' => 'Have you installed your ssh key with the repository yet?',
                    'prompt' => 'Please enter a url for the git repository'
                ]
            ]
        ],
        'deploy' => [
            'directory' => '/var/www/html',
            'commands' => [
                'cd' => [
                    'prompt' => 'Please enter a folder name of the site'
                ],
                'git pull origin master'
            ]
        ]
    ]
];