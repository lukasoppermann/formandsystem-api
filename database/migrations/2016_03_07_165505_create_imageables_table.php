<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImageablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imageables', function (Blueprint $table) {
            $table->increments('id');

            $table->uuid('image_id')->index();
            $table->foreign('image_id')->references('id')->on('images')->onDelete('cascade');

            $table->uuid('imageable_id')->index();
            $table->string('imageable_type');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('imageables');
    }
}
