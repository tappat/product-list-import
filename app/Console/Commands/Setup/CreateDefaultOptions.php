<?php

namespace App\Console\Commands\Setup;

use App\Option;
use Illuminate\Console\Command;

class CreateDefaultOptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'options:default';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install default options';
    public $defaultData = [
      [
        'name' => 'Network import status',
        'value' => 'active'
      ],
      [
        'name' => 'Advertiser import status',
        'value' => 'active'
      ],
      [
        'name' => 'Temp folder',
        'value' => 'temp'
      ],
    ];

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
      $this->info('IN OPTIONS DEFAULT');

      foreach($this->defaultData as $data){
        $data['slug'] = str_slug($data['name']);
        $createdRow = Option::create($data);
      }
    }
}
