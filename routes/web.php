<?php

use App\Brand;
use App\Network;
use App\Product;
use App\Category;
use App\Advertiser;
use App\AttributeValue;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
  $attribute = AttributeValue::find(3);
  dd($attribute->products);
  $product = Product::find(1);
  $attributeValues = AttributeValue::where('advertiser_id', $product->advertiser_id)->take(2)->get();
  $product->attributeValues()->attach($attributeValues);
  dd($attributeValues);
  dd($product->attributevalues);
});
