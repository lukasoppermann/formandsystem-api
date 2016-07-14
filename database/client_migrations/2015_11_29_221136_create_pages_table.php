<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->uuid('id')->index();
            $table->uuid('page_id')->index()->nullable();
            $table->uuid('collection_id')->index()->nullable();
            $table->integer('position')->nullable();
            $table->string('menu_label')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('slug')->index()->nullable();
            $table->boolean('published');
            $table->string('language', 2)->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
			$table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('pages');
    }
}
