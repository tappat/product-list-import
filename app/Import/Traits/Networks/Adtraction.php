<?php
namespace App\Import\Traits\Networks;

use App\Import\Advertiser;

Trait Adtraction{
  var $fields = [
    'external_id' => [
      'external' => 'SKU',
      'internal' => 'external_id',
      'required' => true
    ],
    [
      'external' => 'Name',
      'internal' => 'name',
      'required' => true
    ],
    [
      'external' => 'ProductUrl',
      'internal' => 'url',
      'urlEncode' => true,
      'required' => true,
      'valid' => ['url']
    ],
    [
      'external' => 'Description',
      'internal' => 'description'
    ],
    [
      'external' => 'Category',
      'internal' => 'categories'
    ],
    [
      'external' => 'Price',
      'internal' => 'price',
      'required' => true,
      'valid' => ['number']
    ],
    [
      'external' => 'Instock',
      'internal' => 'instock'
    ],
    [
      'external' => 'ImageUrl',
      'internal' => 'image',
      'urlEncode' => true,
      'required' => true,
      'valid' => ['imageUrl', 'notInBannedImages']
    ],
    [
      'external' => 'Brand',
      'internal' => 'brand',
    ],
    [
      'external' => 'OriginalPrice',
      'internal' => 'base_price',
      'required' => false,
      'valid' => ['number']
    ],
    [
      'external' => 'Ean',
      'internal' => 'ean',
      'required' => false
    ],
    [
      'external' => 'ManufacturerArticleNumber',
      'internal' => 'manufacturerarticlenumber',
      'required' => false
    ],
    [
      'external' => 'Extras',
      'internal' => 'extras',
      'required' => false,
      'valid' => ['array']
    ],
  ];
  var $bannedImages = [];

  function beforeLoop()
  {
    parent::beforeLoop();
    return true;
  }
}