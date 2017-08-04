## Laravel Remote Deploy

This package allows you to define a remote server and directory that you can then push your changes to with a simple command directly from your local.

It allows you to create groups of commands into tasks so they can easily be ran on a remote server. This can be used for deployment, provisioning, health checks and much more.

### Installation

You will need composer to install this package (get composer). Then run:

```bash
composer require jsefton/laravel-remote-deploy
```

#### Register Service Provider

Add the below into your `config/app.php` within `providers` array

```
Jsefton\LaravelRemoteDeploy\LaravelRemoteDeployProvider::class
```

After installation you will need to publish the config file which will allow you to specify your own list of environments. To do this run:

```bash
php artisan vendor:publish --tag=laravel-remote-deploy
```

This will create the file `config/laravel-remote-deploy.php` where you can configure your list of environments.


### Configuration

Inside `config/laravel-remote-deploy.php` you will have 2 sets of configurations. 

This includes `servers`, which is an array of all the possible connections you will want to create and connect too.

This also includes a set of `tasks` that you can then select from to run. It can contain multiple commands and feature file uploads. Below is an example config for setup and deploy:

```php
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
```

### Usage

When in the CLI run the below to execute the command and begin the prompts

```bash
php artisan remote:tasks
```

To clear out any stored credentials in temporary files run:

```bash
php artisan remote:clear
```

### TODO
- Include Migrate Environments package to allow easy database updating with deploy
- Add ability to run same task on multiple servers


### Provision task

THIS IS CURRENTLY IN DEVELOPMENT

By adding the below in your tasks config it will allow you to provision a basic LEMP stack
```php
'reboot' => [
    'directory' => '/',
    'commands' => [
        'reboot'
    ]
],
'provision' => [
    'directory' => '/',
    'commands' => [
        'sudo apt-get update',
        'sudo apt-get install nginx -y',
        'sudo apt-get install mysql-server -y',
        'sudo apt-get install php-fpm php-mysql -y'
    ],
    'files' => [
        [
            'path' => '/etc/nginx/sites-available/default',
            'content' => 'server {
listen 80 default_server;
listen [::]:80 default_server;

root /var/www/html;
index index.php index.html index.htm index.nginx-debian.html;

server_name server_domain_or_IP;

location / {
    try_files $uri $uri/ =404;
}

location ~ \.php$ {
    include snippets/fastcgi-php.conf;
    fastcgi_pass unix:/run/php/php7.0-fpm.sock;
}

location ~ /\.ht {
    deny all;
}
}',
            'after' => [
                'sudo nginx -t',
                'sudo systemctl reload nginx'
            ]
        ]
    ]
]

```
