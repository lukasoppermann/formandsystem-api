<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function($table)
		{
			$table->increments('id');
			$table->string('email', 100)->unique();
	    $table->string('password', 64);
	    $table->timestamps();
			$table->string('service_url', 255)->unique();
			$table->string('service_name', 255)->unique();
			$table->string('service_user', 255)->unique();
			$table->string('service_key', 255)->unique();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
