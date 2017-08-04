<?php

namespace Jsefton\LaravelRemoteDeploy\Console;

use Collective\Remote\RemoteFacade as SSH;
use Illuminate\Console\Command;

class RemoteTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remote:tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs tasks on a remote server';

    /**
     * The set server to target
     * @var
     */
    protected $server;

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
        // Ask for server to connect to (comes from config/laravel-remote-deploy.php within servers array)
        $this->server = $this->choice('Please select a server to connect too?', config('laravel-remote-deploy.servers'));
        $this->info('Server: ' . $this->server);

        // Stored json file of credentials (Note the storage folder should be ignored by git, therefore not committed)
        $filePath = storage_path() . "/app/laravel-deploy-remote-" . $this->server . ".json";


        // Check if we have previously saved details for the set server
        if(file_exists($filePath)) {
            if ($this->confirm('Settings for this server have been found, do you want to use stored settings?')) {
                $details = json_decode(file_get_contents($filePath), true);
            }
        }

        // If not stored, or said no to using stored details, then re-ask for all the needed information
        if(!isset($details)) {

            $details = [
                'host'      => '',
                'username'  => '',
                'password'  => '',
                'key'       => '',
                'keytext'   => '',
                'keyphrase' => '',
                'agent'     => '',
                'timeout'   => 10,
            ];

            $details['host'] = $this->ask('Please enter the server host or IP address');
            $details['username'] = $this->ask('Please enter the username');
            $details['password'] = $this->secret('Please enter the password');

            // Store the server credentials in a json file inside storage/app
            file_put_contents($filePath, json_encode($details));
        }

        // Create connection to the server
        $connection = SSH::connect($details);


        $tasks = config('laravel-remote-deploy.tasks');

        $taskList = [];
        if($tasks) {
            foreach ($tasks as $task => $commands) {
                $taskList[] = $task;
            }

            $task = $this->choice('Please select a task to run on ' . $this->server, $taskList);
            if ($task) {
                $this->info("Setting up: " . $task);
                $baseDirectory = $tasks[$task]['directory'];
                $commands = $tasks[$task]['commands'];


                $this->info('Running: ' . $task);
                $this->runCommands($connection, $commands, $baseDirectory);

                if(isset($tasks[$task]['files'])) {
                    $this->info('Uploading files to remote...');
                    $files = $tasks[$task]['files'];
                    foreach($files as $file) {
                        $this->line(" - Uploaded to: " . $file['path']);
                        $connection->putString($file['path'], $file['content']);
                        if(isset($file['after'])) {
                            $this->runCommands($connection, $file['after'], $baseDirectory);
                        }
                    }
                }

                $this->info("Finished: " . $task);
            }
        }

    }

    protected function runCommands($connection, $commands, $baseDirectory = false)
    {
        if($baseDirectory) {
            $taskCommands = [
                'cd ' . $baseDirectory
            ];
        } else {
            $taskCommands = [];
        }
        foreach ($commands as $command => $options) {
            if(is_array($options)) {
                if (isset($options['prompt'])) {
                    $commandExtra = $this->ask($options['prompt']);
                    $command .= " " . $commandExtra;
                }

                if (isset($options['confirm'])) {
                    if (!$this->confirm($options['confirm'])) {
                        continue;
                    }
                }
            } else {
                $command = $options;
            }
            $taskCommands[] = $command;
        }

        $connection->run($taskCommands, function($line) {
            echo $line.PHP_EOL;
        });
    }
}
