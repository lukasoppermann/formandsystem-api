<?php

use Illuminate\Database\Seeder;

class CmsUser extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('oauth_clients')->insert([
			'id' 		=> 'formandsystem',
			'secret' 	=> bin2hex(random_bytes(30)),
			'name' 		=> 'Form&System'
		]);
		DB::table('oauth_client_scopes')->where('client_id', 'formandsystem')->delete();

		DB::table('oauth_client_scopes')->insert(
			[
				[
					'client_id' => 'formandsystem',
					'scope_id' => 'client.create',
				],
				[
					'client_id' => 'formandsystem',
					'scope_id' => 'client.delete',
				],
				[
					'client_id' => 'formandsystem',
					'scope_id' => 'client.get',
				]
			]
		);
	}

}
