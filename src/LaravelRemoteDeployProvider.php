<?php

namespace JSefton\LaravelRemoteDeploy;

use Illuminate\Support\ServiceProvider;
use JSefton\LaravelRemoteDeploy\Console\RemoteConfigClear;
use JSefton\LaravelRemoteDeploy\Console\RemoteTasks;

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

            $this->app->register( \Collective\Remote\RemoteServiceProvider::class);

            $this->commands([
                RemoteTasks::class,
                RemoteConfigClear::class
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
