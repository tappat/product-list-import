<?php

namespace App;

use App\Import;
use Carbon\Carbon;
use App\Advertiser;
use App\Traits\HasStatus;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;

class ImportPhase extends Model
{
  use HasStatus;
	protected $fillable = ['start_time', 'end_time', 'extra', 'comment', 'duration', 'running', 'advertiser_id', 'import_id', 'status_id'];
  protected $hidden = ['created_at', 'updated_at'];
  protected $dates = ['start_time', 'end_time'];

  public static function initPhase($comment = '', $advertiser_id, $running = true, $status = 'running')
  {
    $created = self::create([
      'comment' => $comment,
      'start_time' => Carbon::now(),
      'running' => $running,
      'advertiser_id' => $advertiser_id,
      'import_id' => Config::get('import')->id
    ]);
    $created->setStatus($status);
    $created->save();
    return $created->id;
  }

  public static function endPhase($id, $status = 'done', $extra = false)
  {
    $phase = self::find($id);
    $phase->end_time = Carbon::now();
    $phase->running = false;
    $phase->duration = gmdate('H:i:s', $phase->end_time->diffInSeconds($phase->start_time));
    if($extra) $phase->extra = $extra;
    $phase->setStatus($status);
    $phase->save();
  }


  public function advertiser()
  {
      return $this->belongsTo(Advertiser::class);
  }

  public function import()
  {
      return $this->belongsTo(Import::class);
  }

}
