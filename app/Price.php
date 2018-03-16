<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
	protected $fillable = ['value', 'product_id'];
  protected $hidden = ['created_at', 'updated_at'];

}
