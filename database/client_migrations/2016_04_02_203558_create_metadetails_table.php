<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMetadetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metadetails', function (Blueprint $table) {
            $table->uuid('id')->index();
            $table->string('type');
            $table->text('data');

            $table->uuid('metadetailable_id')->index();
            $table->string('metadetailable_type');

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
        Schema::drop('metadetails');
    }
}
