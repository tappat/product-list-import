<?php
namespace App\Helpers;

use Ixudra\Curl\Facades\Curl;
use Chumper\Zipper\Facades\Zipper;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Nathanmac\Utilities\Parser\Facades\Parser;

class Helpers {

  public static function downloadProductFile($src)
  {
    $dstPath = Storage::disk('temp')->path('');
    File::cleanDirectory($dstPath);
    $dstFile = Storage::disk('temp')->path('tempfile');
    $response = Curl::to($src)
    ->returnResponseObject()
    ->download($dstFile);
    if($response && $response->status == 200) return $dstFile;
    return false;
  }

  public static function getPlainFile($src)
  {
    $fileMime = @File::mimeType($src);
    if(!$fileMime) return false;
    switch ($fileMime){
      case 'application/zip':
        return self::unzipFile($src);
      break;
    }
  }

  public static function getProductData($src)
  {
    $fileMime = @File::mimeType($src);
    if(!$fileMime) return false;
    $contents = @File::get($src);
    switch ($fileMime){
      case 'text/xml':
        return @Parser::xml($contents);
      break;
    }
  }

  public static function unzipFile($src)
  {
    @Zipper::make($src)->extractTo(Storage::disk('temp')->path('unzip'));
    $files = @File::files(Storage::disk('temp')->path('unzip'));
    return isset($files['0']) ? $files['0'] : false;
  }

  public static function fileSize($file)
  {
    return @File::size($file);
  }

}