<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStreamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('streams', function(Blueprint $table)
		{
			// storage engine
			$table->engine = 'MyISAM';
			// fields
			$table->string('id',255)->unique();
            $table->string('type',255);
			$table->string('page_id',255);
			$table->integer('position');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('streams');
    }
}
