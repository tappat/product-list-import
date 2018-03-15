<?php

namespace App;

use App\ImportPhase;
use App\Traits\HasStatus;
use Illuminate\Database\Eloquent\Model;

class Import extends Model
{
  use HasStatus;
	protected $fillable = ['start_time', 'end_time', 'duration', 'running', 'status_id'];
  protected $hidden = ['created_at', 'updated_at'];
  protected $dates = ['start_time', 'end_time'];

  public function importPhases()
  {
      return $this->hasMany(ImportPhase::class);
  }
}
