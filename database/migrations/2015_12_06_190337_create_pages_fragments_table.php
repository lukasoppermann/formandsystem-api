<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesFragmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages_fragments', function (Blueprint $table) {
            $table->increments('id');

            $table->uuid('page_id')->index();
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');

            $table->uuid('fragment_id')->index();
            $table->foreign('fragment_id')->references('id')->on('fragments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pages_fragments');
    }
}
