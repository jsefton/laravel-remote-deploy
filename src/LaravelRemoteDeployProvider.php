<?php

namespace Jsefton\LaravelRemoteDeploy;

use Illuminate\Support\ServiceProvider;
use Jsefton\LaravelRemoteDeploy\Console\DeployRemote;

class LaravelRemoteDeployProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Register the command
        if ($this->app->runningInConsole()) {
            $this->commands([
                DeployRemote::class
            ]);

            // Publish config file to config folder
            $this->publishes([
                __DIR__.'/../config/laravel-remote-deploy.php' => config_path('laravel-remote-deploy.php')
            ], 'laravel-remote-deploy');
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
