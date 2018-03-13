<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('external_id')->index();
            $table->string('name')->nullable();
            $table->string('url')->nullable();
            $table->boolean('instock')->default(1);
            $table->integer('brand_id')->unsigned()->default(0);
            $table->integer('advertiser_id')->unsigned()->default(0);
            $table->integer('status_id')->unsigned()->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
