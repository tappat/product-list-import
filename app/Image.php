<?php

namespace App;

use App\Traits\HasStatus;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
  use HasStatus;
	protected $fillable = ['url', 'options', 'product_id', 'status_id'];
  protected $hidden = ['created_at', 'updated_at'];

}
