<?php

use App\Brand;
use App\Import;
use App\Option;
use App\Network;
use App\Product;
use App\Category;
use Carbon\Carbon;
use App\Advertiser;
use App\AttributeValue;
use App\Helpers\Helpers;
use Chumper\Zipper\Facades\Zipper;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

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

