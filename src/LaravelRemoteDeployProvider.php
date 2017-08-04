<?php

namespace Jsefton\LaravelRemoteDeploy;

use Illuminate\Support\ServiceProvider;

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
            /*$this->commands([

            ]);*/
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
