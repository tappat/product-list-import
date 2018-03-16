<?php
namespace App\Import;

use App\Error;
use App\Image;
use App\Brand;
use App\Stock;
use App\Price;
use App\Product;
use App\Category;
use Carbon\Carbon;
use App\Attribute;
use App\ImportPhase;
use App\AttributeValue;
use App\Helpers\Helpers;
use App\Advertiser as AdvertiserModel;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

class Advertiser {
  function __construct(AdvertiserModel $advertiserModel, $file = false, $force = false)
  {
    $this->advertiser = $advertiserModel;
    $this->done = false;
    $this->doneMessage = false;
    $this->file = $file;
    $this->force = $force;
    $this->init();

    $productFile = $this->getProductData();
    if(!$productFile){$this->done == true; $this->end('failed', 'Product data'); return false;}
    if($this->done == true && $this->doneMessage){ $this->end('done', $this->doneMessage); return false;}

    $this->beforeLoop();
    if($this->done == true && $this->doneMessage){ $this->end('done', $this->doneMessage); return false;}
    $this->productLoop();
    echo 'dwdw';
    $this->afterLoop();
    if($this->done == true && $this->doneMessage){ $this->end('done', $this->doneMessage); return false;}
    $this->advertiser->setOption('productFileSize', $this->productFileSize);
    $this->advertiser->save();
    $this->end();
  }

  function init()
  {
    $this->importPhase = ImportPhase::initPhase('Initializing Advertiser Import', $this->advertiser->id);
  }

  function getProductData()
  {
    $downloadPhase = ImportPhase::initPhase('Getting product file', $this->advertiser->id);

    if(!$this->file){
      $downloadUrl = $this->advertiser->downloadProductFileUrl();
      $this->file = Helpers::downloadProductFile($downloadUrl);
      if(!$this->file){ ImportPhase::endPhase($downloadPhase, 'failed', 'Download'); return false; }
    }

    $plainFile = Helpers::getPlainFile($this->file);
    if(!$plainFile){ ImportPhase::endPhase($downloadPhase, 'failed', 'Getting plain file'); return false; }

    $this->productFileSize = Helpers::fileSize($plainFile);

    if($this->productFileSize == 0){ ImportPhase::endPhase($downloadPhase, 'failed', 'File size zero'); return false; }
    if($this->productFileSize == $this->advertiser->option('productFileSize') && $this->force == false){ $this->done = true; ImportPhase::endPhase($downloadPhase, 'done', 'No updates'); $this->doneMessage = 'No updates'; return true; }

    $this->productData = Helpers::getProductData($plainFile);
    if(!$this->productData){ ImportPhase::endPhase($downloadPhase, 'failed', 'Getting product data'); return false; }

    ImportPhase::endPhase($downloadPhase, 'done');
    return true;
  }

  function end($status = 'done', $extraComment = false)
  {
    ImportPhase::endPhase($this->importPhase, $status, $extraComment);
  }

  function beforeLoop(){
    $advertiserId = $this->advertiser->id;
    $this->attributes = Attribute::with(['attributevalues' => function($q) use($advertiserId){
      $q->where('advertiser_id', $advertiserId);
    }])->get();
    $back = [];
    foreach($this->attributes as $key => $attribute){
      $back[Helpers::strToLower($attribute->name)]['id'] = $attribute->id;
      $back[Helpers::strToLower($attribute->name)]['values'] = $attribute->attributevalues->pluck('id', 'name')->toArray();
    }
    $this->attributes = $back;
  }

  function afterLoop(){return true;}

  function buildProduct($product)
  {
    if(!isset($product[$this->fields['external_id']['external']]) ||
    Helpers::clean($product[$this->fields['external_id']['external']]) == ''){
      Error::add($this->advertiser->id, 'No external id');
      return false;
    }

    $externalId = Helpers::clean($product[$this->fields['external_id']['external']]);

    $returnProduct = new \stdClass();
    $externalId = $product[$this->fields['external_id']['external']];
    foreach($this->fields as $field){
      $valid = true;
      if(isset($field['required'])){
        if($field['required'] == true && !isset($product[$field['external']]) ||
        $field['required'] == true && Helpers::clean($product[$field['external']]) == ''){
          Error::add($this->advertiser->id, $externalId, $field['external'].' empty or not set');
          return false;
        }

        if(isset($field['valid']) &&
        !empty($field['valid'])){
          $valid = Helpers::validate($product[$field['external']], $field['valid'], $this->bannedImages);
        }

        if($valid == false && $field['required'] == true){
          Error::add($this->advertiser->id, $externalId, $field['external'].' required and not valid: '.$product[$field['external']]);
          return false;
        }elseif($valid == false && $field['required'] == false){
          $product[$field['external']] = '';
        }
      }
      if(isset($field['urlEncode']) && $field['urlEncode'] == true){
        $product[$field['external']] = Helpers::urlEncode($product[$field['external']]);
      }
      $returnProduct->{$field['internal']} = is_array($product[$field['external']]) ? $product[$field['external']] : Helpers::clean($product[$field['external']]);
    }
    return $returnProduct;
  }

  function updateProduct($newProduct)
  {
    $product = Product::with('categories', 'attributeValues', 'images', 'stock')->where('external_id', $newProduct->external_id)->first();
    $updated = false;
    if($product->price != $newProduct->price){
      Price::create([
        'product_id' => $product->id,
        'value' => $product->price
      ]);
    }
    $skip = ['id', 'external_id', 'created_at', 'updated_at'];
    foreach($product->getAttributes() as $key => $value){
      if(in_array($key, $skip)) continue;
      if(isset($newProduct->$key) && $product->$key != $newProduct->$key){
        if($key == 'options' && Helpers::compareJson($newProduct->$key, $product->$key) == false) continue;
        $product->$key = $newProduct->$key;
        $updated = true;
      }
    }

    if($product->brand->name != $newProduct->brand){
      $brand = Brand::firstOrCreate([
        'name' => $newProduct->brand,
        'advertiser_id' => $this->advertiser->id
      ]);
      if($brand->wasRecentlyCreated == true){
        $brand->setStatus('active');
        $brand->save();
      }
      $product->brand_id = $brand->id;
      $updated = true;
    }

    $categoryNames = $product->categories->pluck('name')->toArray();
    $newArray = array_flip($newProduct->categories);
    foreach($categoryNames as $categoryName){
      if(isset($newArray[$categoryName])) {unset($newArray[$categoryName]);}
    }

    if(!empty($newArray)){
      $sync = Category::whereOrCreate($newArray, $this->advertiser->id);
      $product->categories()->sync($sync);
      $updated = true;
    }

    if(isset($newProduct->extras) && !empty($newProduct->extras)){
      $this->addRemoveAttributeValues($product, $newProduct);
    }

    if($updated == true){
      $product->save();
    }
  }

  function createOrUpdateProduct($newProduct)
  {
    //\Illuminate\Support\Facades\DB::enableQueryLog();
    $product = Product::where('external_id', $newProduct->external_id)->first();
    if($product){
      $this->updateProduct($newProduct);
      return true;
    }
    //dd(\Illuminate\Support\Facades\DB::getQueryLog());
    $brand = Brand::firstOrCreate([
      'name' => $newProduct->brand,
      'advertiser_id' => $this->advertiser->id
    ]);
    if($brand->wasRecentlyCreated == true){
      $brand->setStatus('active');
      $brand->save();
    }

    $product = Product::create([
      'external_id' => $newProduct->external_id,
      'name' => $newProduct->name,
      'url' => $newProduct->url,
      'description' => $newProduct->description,
      'options' => isset($newProduct->options) ? $newProduct->options : '',
      'instock' => $newProduct->instock,
      'price' => $newProduct->price,
      'base_price' => $newProduct->base_price,
      'brand_id' => $brand->id
    ]);

    $sync = Category::whereOrCreate($newProduct->categories, $this->advertiser->id);
    $product->categories()->sync($sync);
    $product->setStatus('new');
    $product->save();

    Price::create([
      'product_id' => $product->id,
      'value' => $product->price
    ]);

    foreach($newProduct->image as $image){
      $created = Image::create([
        'product_id' => $product->id,
        'url' => $image
      ]);
      $created->setStatus('new');
      $created->save();
    }

    if(isset($newProduct->extras) && !empty($newProduct->extras)){
      $this->addRemoveAttributeValues($product, $newProduct);
    }
  }

  function addRemoveAttributeValues($product, $newProduct)
  {
    $existing = [];
    if($product->wasRecentlyCreated == false){
      $existing = $product->attributeValues->pluck('id')->toArray();
    }
    $sync = [];
    foreach($newProduct->extras as $key => $values){
      if($key == 'stock_level'){
        if($product->wasRecentlyCreated || $product->stock->value != $values){
          Stock::create([
            'value' => $values,
            'product_id' => $product->id
          ]);
        }
        continue;
      }

      if(!isset($this->attributes[$key])){
        $created = Attribute::create([
          'name' => $key
        ]);
        $this->attributes[$key]['id'] = $created->id;
        $this->attributes[$key]['values'] = [];
      }
      $attributeId = $this->attributes[$key]['id'];
      foreach($values as $value){
        $name = Helpers::strToLower(Helpers::clean($value));
        if(isset($this->attributes[$key]['values'][$value])){
          $sync[] = $this->attributes[$key]['values'][$value];
          continue;
        }
        $created = AttributeValue::create([
          'name' => $name,
          'advertiser_id' => $this->advertiser->id,
          'attribute_id' => $this->attributes[$key]['id']
        ]);
        $created->setStatus('new');
        $created->save();
        $this->attributes[$key]['values'][$created->name] = $created->id;
        $sync[] = $created->id;
      }
    }
    if(array_diff($sync, $existing)){
      $product->attributevalues()->sync($sync);
    }
  }
}