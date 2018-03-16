<?php

namespace App;

use App\Network;
use App\Product;
use App\Traits\HasStatus;
use App\Traits\HasOptions;
use Illuminate\Database\Eloquent\Model;

class Advertiser extends Model
{
    use HasStatus, HasOptions;
    protected $fillable = ['name', 'url', 'slug', 'options', 'network_id', 'status_id'];
    protected $hidden = ['created_at', 'updated_at'];


    public function downloadProductFileUrl()
    {
      $productListSyntax = $this->network->option('productListSyntax');
      $advertiserId = $this->option('advertiserId');
      $publisherSiteId = $this->option('publisherSiteId') ? $this->option('publisherSiteId') : $this->network->option('defaultPublisherSiteId');
      $url = preg_replace('#%ADVERTISER_ID%#', $advertiserId, $productListSyntax);
      return preg_replace('#%PUBLISHER_SITE_ID%#', $publisherSiteId, $url);
    }

    public function advertisers()
    {
        return $this->hasMany(Product::class);
    }

    public function network()
    {
        return $this->belongsTo(Network::class);
    }

}
