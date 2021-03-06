<?php

namespace App\Console\Commands\Setup;

use App\Network;
use App\Advertiser;
use Illuminate\Console\Command;

class CreateDefaultAdvertisers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'advertisers:default';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install default advertisers';
    public $defaultData = [
      [
        'network' => 'Adtraction',
        'fields' => [
          'name' => 'Stayhard',
          'url' => 'https://stayhard.se/',
          'options' => '{
            "advertiserId": "1055222007",
            "defaultGender": "herr"
          }'
        ]
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
      $this->info('IN ADVERTISERS DEFAULT');

      foreach($this->defaultData as $data){
          $data['fields']['slug'] = camel_case($data['fields']['name']);
          $createdRow = Advertiser::create($data['fields']);
          $network = Network::where('name', $data['network'])->first();
          if($network){
            $createdRow->network()->associate($network);
          }
          $createdRow->setStatus('active');
          $createdRow->save();
      }
    }
}
