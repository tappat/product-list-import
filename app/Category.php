<?php

namespace App;

use App\Product;
use App\Advertiser;
use App\Traits\HasStatus;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  use HasStatus;

	protected $fillable = ['name', 'advertiser_id', 'status_id'];
  protected $hidden = ['created_at', 'updated_at'];


  public function advertiser()
  {
      return $this->belongsTo(Advertiser::class);
  }

  public function products()
  {
      return $this->belongsToMany(Product::class);
  }
}
