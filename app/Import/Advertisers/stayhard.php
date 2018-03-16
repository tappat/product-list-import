<?php
namespace App\Import\Advertisers;

use App\Helpers\Helpers;
use App\Import\Advertiser;
use App\Advertiser as AdvertiserModel;
use App\Import\Traits\Networks\Adtraction;

class stayhard extends Advertiser{
  use Adtraction;

  function __construct(AdvertiserModel $advertiserModel, $file = false)
  {
    parent::__construct($advertiserModel, $file);
  }

  function productLoop()
  {
    $limit = 10;
    $n = 0;
    foreach($this->productData['product'] as $productData){
      $n += 1;
      //if($n > $limit){continue;}
      $newProduct = $this->buildProduct($productData);
      if(!$newProduct){continue;}

      $newProduct->categories = array($newProduct->categories);
      $newProduct->instock = $newProduct->instock == 'no' ? 0 : 1;
      $newProduct->extras = Helpers::mergeExtraArray($newProduct->extras);

      if(isset($newProduct->extras['colour'])){
        $newProduct->extras['color'] = array($newProduct->extras['colour']);
        unset($newProduct->extras['colour']);
      }

      if(isset($newProduct->extras['size'])){
        $newProduct->extras['size'] = array($newProduct->extras['size']);
      }

      $newProduct->extras['gender'] = array($this->advertiser->option('defaultGender'));

      $newProduct->options = [
        'manufacturerarticlenumber' => $newProduct->manufacturerarticlenumber,
        'ean' => $newProduct->ean
      ];

      $newProduct->image = [$newProduct->image];

      ksort($newProduct->options);
      $newProduct->options = json_encode($newProduct->options);
      $this->createOrUpdateProduct($newProduct);

      //Helpers::a($newProduct);
      //Helpers::a($productData);
    }
  }

}