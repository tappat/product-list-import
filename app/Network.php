<?php

namespace App;

use App\Advertiser;
use App\Traits\HasStatus;
use Illuminate\Database\Eloquent\Model;

class Network extends Model
{
  use HasStatus;

	protected $fillable = ['name', 'url', 'options', 'status_id'];
  protected $hidden = ['created_at', 'updated_at'];


  public function advertisers()
  {
      return $this->hasMany(Advertiser::class);
  }
}
