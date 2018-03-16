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
    return $src;
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

  public static function clean($str)
  {
    $str = self::trim($str);
    return iconv(mb_detect_encoding($str, mb_detect_order(), true), "UTF-8", $str);
  }

  public static function urlEncode($url)
  {
    $url = urlencode($url);
    $url = str_replace("%3A", ":", $url);
    $url = str_replace("%2F", "/", $url);
    $url = str_replace("%3F", "?", $url);
    $url = str_replace("%23", "#", $url);
    return $url;
  }

  public static function validate($subject, $valid, $invalidArray = array())
  {
    foreach($valid as $va){
      $val = self::validateSwitch($subject, $va, $invalidArray);
      if(!$val) return false;
    }
    return true;
  }

  public static function validateSwitch($subject, $type, $invalidArray = array())
  {
    switch($type){
      case 'array':
        return is_array($subject);
        break;
      case 'number':
        return is_numeric($subject);
        break;
      case 'integer':
        return is_int($subject);
        break;
      case 'string':
        return is_string($subject);
        break;
      case 'url':
        return filter_var($subject, FILTER_VALIDATE_URL);
        break;
        case 'imageUrl':
          return filter_var($subject, FILTER_VALIDATE_URL);
          break;
      case 'notInBannedImages':
        return !in_array($subject, $invalidArray);
        break;
    }
  }

  public static function mergeExtraArray($extra)
  {
    if(!isset($extra['Name']) || !is_array($extra['Name'])) return [];
    $back = array();
    foreach($extra['Name'] as $key => $name){
      $back[self::strToLower($name)] = self::strToLower($extra['Value'][$key]);
    }
    return $back;
  }

  public static function compareJson($json1, $json2)
  {
    $arr1 = (array) @json_decode($json1);
    $arr2 = (array) @json_decode($json2);
    $difference1 = array_diff_assoc($arr1, $arr2);
    $difference2 = array_diff_assoc($arr2, $arr1);
    $diff = $difference1+$difference2;
    return empty($diff) ? false : true;
  }

  public static function strToLower($str)
  {
    return mb_strtolower($str, 'UTF-8');
  }

  public static function trim($str)
  {
    return trim($str);
  }

  public static function a($array)
  {
    echo '<pre>';
    print_r($array);
    echo '</pre>';
  }

}