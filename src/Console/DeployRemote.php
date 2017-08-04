<?php

namespace Jsefton\LaravelRemoteDeploy\Console;

use Collective\Remote\RemoteFacade as SSH;
use Illuminate\Console\Command;

class DeployRemote extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deploy:remote';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deploys current site to a remote server';

    /**
     * The set environment to target
     * @var
     */
    protected $env;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Ask for environment target (simply add more to support additional environment targets)
        $this->env = $this->choice('Please select an environment target?', config('laravel-remote-deploy.environments'));
        $this->info('Environment: ' . $this->env);

        // Stored json file of credentials (Note the storage folder should be ignored by git, therefore not committed)
        $filePath = storage_path() . "/app/laravel-deploy-remote-" . $this->env . ".json";


        $tasks = config('laravel-remote-deploy.tasks');
        if($tasks) {
            foreach($tasks as $task => $commands) {
                $this->info("Setting up: " . $task);

                $taskCommands = [];

                foreach($commands as $command => $options) {
                    if ($options) {
                        if (isset($options['prompt'])) {
                            $commandExtra = $this->ask($options['prompt'], "master");
                            $command .= " " . $commandExtra;
                        }
                    }
                    $taskCommands[] = $command;
                }

                $this->info('Running: ' . $task);
                print_r($taskCommands);
            }
        }

        SSH::connect([
            'host'      => '192.168.10.10',
            'username'  => 'vagrant',
            'password'  => '',
            'key'       => '',
            'keytext'   => '',
            'keyphrase' => '',
            'agent'     => '',
            'timeout'   => 10,
        ]);

        die;

        // Check if we have previously saved details for the set environment
        if(file_exists($filePath)) {
            if ($this->confirm('Settings for this environment have been found, do you want to use stored settings?')) {
                $details = json_decode(file_get_contents($filePath), true);
            }
        }

        // If not stored, or said no to using stored details, then re-ask for all the needed information
        if(!isset($details)) {


            $details = [];

            // Store the environment credentials in a json file inside storage/app
            file_put_contents($filePath, json_encode($details));
        }
    }
}
