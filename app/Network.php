<?php

namespace App;

use App\Advertiser;
use App\Traits\HasStatus;
use App\Traits\HasOptions;
use Illuminate\Database\Eloquent\Model;

class Network extends Model
{
  use HasStatus, HasOptions;

	protected $fillable = ['name', 'url', 'slug', 'options', 'status_id'];
  protected $hidden = ['created_at', 'updated_at'];


  public function advertisers()
  {
      return $this->hasMany(Advertiser::class);
  }
}
