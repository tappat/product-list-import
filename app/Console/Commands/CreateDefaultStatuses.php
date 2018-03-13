<?php

namespace App\Console\Commands;

use App\Status;
use Illuminate\Console\Command;

class CreateDefaultStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'statuses:default';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Insert systems default statuses';

    public $defaultData = [
        [
            'model_type' => 'App\Network',
            'statuses' => [
                'Active',
                'Disabled',
            ],
          ],
          [
              'model_type' => 'App\Advertiser',
              'statuses' => [
                  'New',
                  'Active',
                  'Disabled',
              ],
          ],
          [
              'model_type' => 'App\Product',
              'statuses' => [
                  'New',
                  'Active',
                  'Disabled',
              ],
          ],
          [
              'model_type' => 'App\Category',
              'statuses' => [
                  'Active',
                  'Disabled',
              ],
          ],
          [
              'model_type' => 'App\Brand',
              'statuses' => [
                  'Active',
                  'Disabled',
              ],
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
        $this->info('IN STATUSES DEFAULT');
        foreach($this->defaultData as $statuses){
            foreach($statuses['statuses'] as $status){
                $status = Status::create([
                    'name' => $status,
                    'slug' => str_slug($status),
                    'model_type' => $statuses['model_type']
                ]);

            }
        }
    }
}
