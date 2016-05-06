<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMetadetailablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metadetailables', function (Blueprint $table) {
            $table->increments('id');

            $table->uuid('metadetail_id')->index();
            $table->foreign('metadetail_id')->references('id')->on('metadetail');

            $table->uuid('metadetailable_id')->index();
            $table->string('metadetailable_type');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('metadetailables');
    }
}
