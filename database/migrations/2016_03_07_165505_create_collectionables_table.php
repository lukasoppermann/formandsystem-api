<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollectionablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collectionables', function (Blueprint $table) {
            $table->increments('id');

            $table->uuid('collection_id')->index();
            $table->foreign('collection_id')->references('id')->on('collection')->onDelete('cascade');

            $table->uuid('collectionable_id')->index();
            $table->string('collectionable_type');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('collectionables');
    }
}
