<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('advertiser_id')->index()->unsigned()->default(0);
            $table->integer('status_id')->index()->unsigned()->default(0);
            $table->timestamps();
        });
        Schema::create('category_product', function (Blueprint $table) {
            $table->integer('category_id')->index()->unsigned();
            $table->integer('product_id')->index()->unsigned();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
        Schema::dropIfExists('category_product');
    }
}
