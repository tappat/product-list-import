<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
	protected $fillable = ['name', 'slug', 'value'];
  protected $hidden = ['created_at', 'updated_at'];
}
