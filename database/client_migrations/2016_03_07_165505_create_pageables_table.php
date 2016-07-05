<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePageablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pageables', function (Blueprint $table) {

            $table->increments('id');

            $table->uuid('page_id')->index();
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');

            $table->uuid('pageable_id')->index();
            $table->string('pageable_type');

            $table->integer('position')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pageables');
    }
}
