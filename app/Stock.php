<?php

namespace App;

use App\Product;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
	protected $fillable = ['value', 'product_id'];
  protected $hidden = ['created_at', 'updated_at'];


  function product()
  {
    return $this->belongsTo(Product::class);
  }

}
