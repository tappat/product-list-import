<?php

namespace App;

use App\Brand;
use App\Category;
use App\Advertiser;
use App\AttributeValue;
use App\Traits\HasStatus;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
  use HasStatus;
	protected $fillable = ['name', 'external_id', 'url', 'instock', 'brand_id', 'advertiser_id', 'status_id'];
  protected $hidden = ['created_at', 'updated_at'];

  public function advertiser()
  {
      return $this->belongsTo(Advertiser::class);
  }

  public function brand()
  {
      return $this->belongsTo(Brand::class);
  }

  public function categories()
  {
      return $this->belongsToMany(Category::class);
  }

  public function attributeValues()
  {
      return $this->belongsToMany(AttributeValue::class);
  }

}
