<?php

use App\Brand;
use App\Stock;
use App\Option;
use App\Import;
use App\Product;
use App\Network;
use App\Category;
use Carbon\Carbon;
use App\Attribute;
use App\Advertiser;
use App\AttributeValue;
use App\Helpers\Helpers;
use Chumper\Zipper\Facades\Zipper;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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
  $import = new \App\Import\Import(true);
  die();
  $url = 'https://stayhard.se/70000443/samsøe-samsøe/laurent-pants-black#';
  $url = Helpers::urlEncode($url);
  $valid = filter_var($url, FILTER_VALIDATE_URL);
  if($valid){
    echo $url;
  }
  die();
  $product = Product::with('stock')->where('id', 1)->first();

  dd($product);
  $asd = Stock::create([
    'product_id' => 20,
    'value' => 20
  ]);
  dd($asd);
  die();
  $advertiserId = 1;
  $attributes = Attribute::with(['attributevalues' => function($q) use($advertiserId){
    $q->where('advertiser_id', $advertiserId);
  }])->get();
  dd($attributes);
  die();
  $attributes = Attribute::with('attributevalues', function($q) use($advertiserId) {
    $q->where('advertiser_id', $advertiserId);
  })->get();
  dd($attributes);
  die();
  /*
  $newCats = ['new cat', 'old cat', 'anothesrs'];
  $sync = Category::whereOrCreate($newCats, 1);
  $product = Product::find(1);
  $product->categories()->sync($sync);
  die();
  */
  /*
  $url = 'https://stayhard.se/04436453/proud-canadian/nash-paint-worn-black';
  $valid = filter_var($url, FILTER_VALIDATE_URL);
  if($valid){
    echo 'true';
  }
  die();
  */
  //$dstFile = Storage::disk('temp')->path('tempfile');
  $dstFile = 'dwdw';
  echo Helpers::getPlainFile($dstFile);
  die();
/*
  $file = Storage::disk('temp')->path('');
  echo $file;
  die();
  File::cleanDirectory($file);
  die();
  $import = new \App\Import\Import(true);
  die();
  $advertiser = Advertiser::find(1);
  echo $advertiser->downloadProductFile();
  die();
  */
  $import = new \App\Import\Import(true);

  /*
  $import = Import::create([
    'start_time' => Carbon::now()->addDays(30),
  ]);
  $import->setStatus('running');
  $import->save();
  */
});

Route::get('/import', function () {
  $file = Storage::disk('local')->path('dled.zip');

  $dlUrl = 'https://adtraction.com/productfeed.htm?type=feed&format=XML&encoding=UTF8&epi=0&zip=1&cdelim=tab&tdelim=singlequote&sd=0&sn=0&apid=52561763&asid=1055222007';
  $response = Curl::to($dlUrl)
  ->returnResponseObject()
  ->download($file);

  echo $response->status;
  die();
  $mimetype = Storage::mimeType('dled.zip');
  echo $mimetype;

  die();
  $file = Storage::disk('local')->path('1.zip');
  $aj = Zipper::make($file)->extractMatchingRegex(Storage::disk('local')->path('unz'), '/\.xml$/i');
  echo ($aj);
  print_r($aj);
  die();
  Zipper::make($file)->extractTo(Storage::disk('local')->path('ajzip'));

  // $import = new \App\Import\Import(true);
});

