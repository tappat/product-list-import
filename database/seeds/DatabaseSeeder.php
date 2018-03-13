<?php

use App\Brand;
use App\Product;
use App\Network;
use App\Category;
use App\Advertiser;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      factory(Network::class, 4)->create()->each(function ($u) {
        $u->setStatus('active');
        $u->save();
      });

      factory(Advertiser::class, 25)->create()->each(function ($u) {
        $network = Network::inRandomOrder()->first();
        $u->network()->associate($network);
        $u->setStatus('active');
        $u->save();
      });

      factory(Category::class, 200)->create()->each(function ($u) {
        $advertiser = Advertiser::inRandomOrder()->first();
        $u->advertiser()->associate($advertiser);
        $u->setStatus('active');
        $u->save();
      });

      factory(Brand::class, 200)->create()->each(function ($u) {
        $advertiser = Advertiser::inRandomOrder()->first();
        $u->advertiser()->associate($advertiser);
        $u->setStatus('active');
        $u->save();
      });

      factory(Product::class, 10000)->create()->each(function ($u) {
        $category = Category::inRandomOrder()->first();
        $u->categories()->attach($category->id);
        $u->advertiser()->associate($category->advertiser);
        $brand = Brand::where('advertiser_id', $category->advertiser->id)->orWhere('advertiser_id', 0)->first();
        if($brand->advertiser_id === 0){
          $brand->advertiser()->associate($category->advertiser);
        }
        $u->brand()->associate($brand);
        $u->setStatus('active');
        $u->save();
      });
    }
}
