<?php

namespace App\Console\Commands\Setup;

use App\Network;
use Illuminate\Console\Command;

class CreateDefaultNetworks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'networks:default';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install default networks';

    /**
     * defualt networks
     *
     * @var array
    */
    public $defaultData = [
      [
        'name' => 'Adtraction',
        'url' => 'https://adtraction.com/',
        'options' => '{
          "defaultPublisherSiteId":"52561763",
          "statusListSyntax":"https://adtraction.com/productfeed.htm?type=status&format=XML&encoding=UTF8&epi=0&zip=0&cdelim=tab&tdelim=singlequote&sd=0&sn=0&apid=%PUBLISHER_SITE_ID%&asid=%ADVERTISER_ID%",
          "productListSyntax":"https://adtraction.com/productfeed.htm?type=feed&format=XML&encoding=UTF8&epi=0&zip=1&cdelim=tab&tdelim=singlequote&sd=0&sn=0&apid=%PUBLISHER_SITE_ID%&asid=%ADVERTISER_ID%"
        }',
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
      $this->info('IN NETWORKS DEFAULT');

      foreach($this->defaultData as $data){
          $data['slug'] = camel_case($data['name']);
          $createdRow = Network::create($data);
          $createdRow->setStatus('active');
          $createdRow->save();
      }
    }
}
