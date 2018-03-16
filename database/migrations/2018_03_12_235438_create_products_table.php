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
            $table->decimal('price', 10, 2)->unsigned()->default(0);
            $table->decimal('base_price', 10, 2)->unsigned()->default(0);
            $table->text('url')->nullable();
            $table->json('options')->nullable();
            $table->text('description')->nullable();
            $table->boolean('instock')->index()->default(1);
            $table->integer('brand_id')->index()->unsigned()->default(0);
            $table->integer('advertiser_id')->index()->unsigned()->default(0);
            $table->integer('status_id')->index()->unsigned()->default(0);
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
