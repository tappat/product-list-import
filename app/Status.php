<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
  protected $fillable = ['name', 'slug', 'model_type'];
  protected $hidden = ['model_type', 'created_at', 'updated_at'];
}
