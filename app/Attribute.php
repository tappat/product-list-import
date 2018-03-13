<?php

namespace App;

use App\AttributeValue;
use App\Traits\HasStatus;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    use HasStatus;
    protected $fillable = ['name', 'status_id'];
    protected $hidden = ['created_at', 'updated_at'];


    public function attributevalues()
    {
        return $this->hasMany(AttributeValue::class);
    }

}
