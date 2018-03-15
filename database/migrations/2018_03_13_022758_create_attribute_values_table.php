<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttributeValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attribute_values', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('advertiser_id')->index()->unsigned()->default(0);
            $table->integer('attribute_id')->index()->unsigned()->default(0);
            $table->integer('status_id')->index()->unsigned()->default(0);
            $table->timestamps();
        });

        Schema::create('attribute_value_product', function (Blueprint $table) {
            $table->integer('attribute_value_id')->index()->unsigned();
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
        Schema::dropIfExists('attribute_values');
    }
}
