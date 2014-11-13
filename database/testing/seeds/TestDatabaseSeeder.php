<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class TestDatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		// --------------------------
		// Content Seeding
		//
		// this seeder truncates DB !
		$this->call('FsContentTableSeeder');
		// additional seeders
		$this->call('ReferencesContentTableSeeder');

		// --------------------------
		// Stream Seeding
		//
		// this seeder truncates DB !
		$this->call('FsStreamTableSeeder');
		// additional seeders only add data
		$this->call('ReferencesStreamTableSeeder');
	}

}
