<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_detail', function (Blueprint $table) {
            $table->increments('id');
            $table->string('detail_id', 40)->index()->nullable();
            $table->foreign('detail_id')->references('id')->on('details')->onDelete('cascade');
            $table->string('client_id', 40)->index()->nullable();
            $table->foreign('client_id')->references('id')->on('oauth_clients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('client_detail');
    }
}
