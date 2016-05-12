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
			],
			[
				'id' => 'content.post',
				'description' => 'create content via api',
				'created_at' => '0000-00-00',
				'updated_at' => '0000-00-00'
			],
			[
				'id' => 'content.delete',
				'description' => 'delete content via api',
				'created_at' => '0000-00-00',
				'updated_at' => '0000-00-00'
			],
			[
				'id' => 'content.get',
				'description' => 'get content via api',
				'created_at' => '0000-00-00',
				'updated_at' => '0000-00-00'
			],
			[
				'id' => 'content.patch',
				'description' => 'patch content via api',
				'created_at' => '0000-00-00',
				'updated_at' => '0000-00-00'
			],
		];

		DB::table('oauth_scopes')->insert($scopes);
	}

}
