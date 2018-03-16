<?php

namespace App;

use App\Product;
use App\Attribute;
use App\Traits\HasStatus;
use Illuminate\Database\Eloquent\Model;

class AttributeValue extends Model
{
    use HasStatus;
    protected $fillable = ['name', 'advertiser_id', 'attribute_id', 'status_id'];
    protected $hidden = ['created_at', 'updated_at'];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
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
