<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFragmentablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fragmentables', function (Blueprint $table) {
            $table->increments('id');

            $table->uuid('fragment_id')->index();
            $table->foreign('fragment_id')->references('id')->on('fragments')->onDelete('cascade');

            $table->uuid('fragmentable_id')->index();
            $table->string('fragmentable_type');


        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('fragmentables');
    }
}
