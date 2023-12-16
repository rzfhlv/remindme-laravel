<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FreshInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fresh-install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fresh Installation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->call('key:generate');
            $this->call('migrate:fresh');
            $this->call('storage:link');
            $this->call('db:seed');
        } catch (\Throwable $th) {
            $this->error('Installation failed: ' . json_encode($th->getMessage()));
        }
    }
}
