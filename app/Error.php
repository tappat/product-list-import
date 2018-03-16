<?php

namespace App;

use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;

class Error extends Model
{
	protected $fillable = ['import_id', 'advertiser_id', 'info', 'external_id'];
  protected $hidden = ['created_at', 'updated_at'];
  protected $dates = ['start_time', 'end_time'];

  public static function add($advertiser_id = '', $external_id = '', $info)
  {
    $created = self::create([
      'info' => $info,
      'external_id' => $external_id,
      'advertiser_id' => $advertiser_id,
      'import_id' => Config::get('import')->id
    ]);
  }
}
