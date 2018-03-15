<?php

namespace App\Console\Commands\Setup;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class FullInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'install:full';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Installs fully';

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
        $this->info('IN INSTALL FULL');
        $this->call('migrate:fresh');
        $this->call('statuses:default');
        $this->call('networks:default');
        $this->call('advertisers:default');
        $this->call('attributes:default');
        $this->call('options:default');
    }
}
