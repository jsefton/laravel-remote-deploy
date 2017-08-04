<?php

namespace Jsefton\LaravelRemoteDeploy\Console;

use Illuminate\Console\Command;

class RemoteConfigClear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remote:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear stored config credentials';

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
        $servers = config('laravel-remote-deploy.servers');
        if($servers) {
            foreach($servers as $server) {
                $filePath = storage_path() . "/app/laravel-deploy-remote-" . $server . ".json";
                if(file_exists($filePath)) {
                    unlink($filePath);
                    $this->info('Removed ' . $server . ' cached config');
                }
            }
        }
    }
}
