<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollectionPageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
     {
         Schema::create('collection_page', function (Blueprint $table) {
             $table->increments('id');

             $table->uuid('collection_id')->index();
             $table->foreign('collection_id')->references('id')->on('collections')->onDelete('cascade');

             $table->uuid('page_id')->index();
             $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
         });
     }
     /**
      * Reverse the migrations.
      *
      * @return void
      */
     public function down()
     {
         Schema::drop('collection_page');
     }
}
