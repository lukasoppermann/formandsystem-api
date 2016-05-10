<?php

use Illuminate\Database\Seeder;

class ScopeTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		$scopes = [
			[
				'id' => 'client.create',
				'description' => 'create an api client',
				'created_at' => '0000-00-00',
				'updated_at' => '0000-00-00'
			],
			[
				'id' => 'client.delete',
				'description' => 'delete an api client',
				'created_at' => '0000-00-00',
				'updated_at' => '0000-00-00'
			],
			[
				'id' => 'client.get',
				'description' => 'get an api client',
				'created_at' => '0000-00-00',
				'updated_at' => '0000-00-00'
			]
		];

		DB::table('oauth_scopes')->insert($scopes);
	}

}
