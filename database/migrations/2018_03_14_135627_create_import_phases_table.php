<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImportPhasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_phases', function (Blueprint $table) {
          $table->increments('id');
          $table->string('comment')->nullable();
          $table->text('extra')->nullable();
          $table->datetime('start_time')->nullable();
          $table->datetime('end_time')->nullable();
          $table->string('duration')->nullable();
          $table->boolean('running')->nullable();
          $table->integer('advertiser_id')->index()->unsigned()->default(0);
          $table->integer('import_id')->index()->unsigned()->default(0);
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
        Schema::dropIfExists('import_phases');
    }
}
