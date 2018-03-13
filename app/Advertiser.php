<?php

namespace App;

use App\Network;
use App\Product;
use App\Traits\HasStatus;
use Illuminate\Database\Eloquent\Model;

class Advertiser extends Model
{
    use HasStatus;
    protected $fillable = ['name', 'url', 'options', 'network_id', 'status_id'];
    protected $hidden = ['created_at', 'updated_at'];

    public function advertisers()
    {
        return $this->hasMany(Product::class);
    }

    public function network()
    {
        return $this->belongsTo(Network::class);
    }

}
