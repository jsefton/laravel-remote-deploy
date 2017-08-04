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

### Usage

When in the CLI run the below to execute the command and begin the prompts

```bash
php artisan remote:tasks
```

### TODO
- Create service provider to register command
- Find or create SSH package for remote connection
- Build ability to create custom defined commands / tasks to be ran on a 'build'
- Include Migrate Environments package to allow easy database updating with deploy
- Enable multiple environments / remote targets to be defined


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
