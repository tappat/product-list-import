<?php

namespace App;

use App\Traits\HasStatus;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
  use HasStatus;

	protected $fillable = ['name', 'advertiser_id', 'status_id'];
  protected $hidden = ['created_at', 'updated_at'];


  public static function findOrCreate()
  {
    self::firstOrCreate();
  }

  public function advertiser()
  {
      return $this->belongsTo(Advertiser::class);
  }

  public function products()
  {
      return $this->hasMany(Product::class);
  }
}
