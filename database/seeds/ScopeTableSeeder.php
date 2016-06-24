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
				'id' => 'client.post',
				'description' => 'create an api client',
				'created_at' => NULL,
				'updated_at' => NULL
			],
			[
				'id' => 'client.delete',
				'description' => 'delete an api client',
				'created_at' => NULL,
				'updated_at' => NULL
			],
			[
				'id' => 'client.get',
				'description' => 'get an api client',
				'created_at' => NULL,
				'updated_at' => NULL
			],
			[
				'id' => 'content.post',
				'description' => 'create content via api',
				'created_at' => NULL,
				'updated_at' => NULL
			],
			[
				'id' => 'content.delete',
				'description' => 'delete content via api',
				'created_at' => NULL,
				'updated_at' => NULL
			],
			[
				'id' => 'content.get',
				'description' => 'get content via api',
				'created_at' => NULL,
				'updated_at' => NULL
			],
			[
				'id' => 'content.patch',
				'description' => 'patch content via api',
				'created_at' => NULL,
				'updated_at' => NULL
			],
		];

		DB::table('oauth_scopes')->insert($scopes);
	}

}
