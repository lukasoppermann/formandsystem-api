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
		$cms_scopes = [
			[
				'id' => 'client.create',
				'description' => 'create an api client',
			],
			[
				'id' => 'client.delete',
				'description' => 'delete an api client',
			],
			[
				'id' => 'client.get',
				'description' => 'get an api client',
			]
		];

		$client_scopes = [

		];

		DB::table('oauth_scopes')->insert(array_merge($cms_scopes, $client_scopes));
	}

}
