<?php

namespace App\Console\Commands;

use App\Attribute;
use Illuminate\Console\Command;

class CreateDefaultAttributes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attributes:default';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert systems default statuses';

    public $defaultData = [
      [
        'name' => 'Color',
      ],
      [
        'name' => 'Gender',
      ]
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
      $this->info('IN ATTRIBUTES DEFAULT');

      foreach($this->defaultData as $data){
          $createdRow = Attribute::create($data);
          $createdRow->setStatus('active');
          $createdRow->save();
      }
    }
}
