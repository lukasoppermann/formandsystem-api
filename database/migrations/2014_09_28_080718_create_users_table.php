<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('email', 100)->unique();
			$table->string('password', 64);
			$table->string('connection_token', 255)->unique();
			$table->string('service_host', 255);
			$table->string('service_url', 255);
			$table->string('service_name', 255);
			$table->string('service_user', 255);
			$table->string('service_key', 255);
			$table->rememberToken();
			$table->timestamps();
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
