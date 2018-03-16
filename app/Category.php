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

  public static function whereOrCreate($newCategories, $advertiser_id)
  {
    $categories = self::where('advertiser_id', $advertiser_id)->whereIn('name', $newCategories)->get();
    if(count($categories) == count($newCategories)){return $categories->pluck('id')->toArray();}
    $newCategories = array_flip($newCategories);
    $sync = [];
    foreach($categories as $category){
      if(isset($newCategories[$category->name])){
        $sync[] = $category->id;
        unset($newCategories[$category->name]);
      }
    }
    $newCategories = array_flip($newCategories);
    foreach($newCategories as $cat){
      $created = self::create([
        'name' => $cat,
        'advertiser_id' => $advertiser_id
      ]);
      $created->setStatus('new');
      $created->save();
      $sync[] = $created->id;
    }
    return $sync;
  }

  public function advertiser()
  {
      return $this->belongsTo(Advertiser::class);
  }

  public function products()
  {
      return $this->belongsToMany(Product::class);
  }
}