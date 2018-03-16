<?php

namespace App;

use App\Brand;
use App\Price;
use App\Image;
use App\Stock;
use App\Category;
use App\Advertiser;
use App\AttributeValue;
use App\Traits\HasStatus;
use App\Traits\HasOptions;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
  use HasStatus, HasOptions;
	protected $fillable = ['name', 'external_id', 'url', 'price', 'base_price', 'options', 'instock', 'description', 'brand_id', 'advertiser_id', 'status_id'];
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

  public function stock()
  {
      return $this->hasOne(Stock::class)->orderBy('id', 'asc');
  }

  public function stocks()
  {
      return $this->hasMany(Stock::class)->latest();
  }

  public function prices()
  {
      return $this->hasMany(Price::class);
  }

  public function images()
  {
      return $this->hasMany(Image::class);
  }

  public function price()
  {
      return $this->hasMany(Price::class)->latest();
  }

}
