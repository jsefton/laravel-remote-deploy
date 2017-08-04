## Laravel Remote Deploy

This package allows you to define a remote server and directory that you can then push your changes to with a simple command directly from your local.

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


### Usage

When in the CLI run the below to execute the command and begin the prompts

```bash
php artisan deploy:remote
```

### TODO
- Create service provider to register command
- Find or create SSH package for remote connection
- Build ability to create custom defined commands / tasks to be ran on a 'build'
- Include Migrate Environments package to allow easy database updating with deploy
- Enable multiple environments / remote targets to be defined
