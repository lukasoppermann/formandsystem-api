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

//
// use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Database\Migrations\Migration;
//
// class CreateCollectionPageTable extends Migration
// {
//     /**
//      * Run the migrations.
//      *
//      * @return void
//      */
//      public function up()
//      {
//          Schema::create('collection_page', function (Blueprint $table) {
//              $table->increments('id');
//
//              $table->uuid('collection_id')->index();
//              $table->foreign('collection_id')->references('id')->on('collections')->onDelete('cascade');
//
//              $table->uuid('page_id')->index();
//              $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
//          });
//      }
//      /**
//       * Reverse the migrations.
//       *
//       * @return void
//       */
//      public function down()
//      {
//          Schema::drop('collection_page');
//      }
// }
