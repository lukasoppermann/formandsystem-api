<?php

use Illuminate\Database\Seeder;

class ClientTableSeeder extends Seeder {

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
			'name' 		=> 'Form&System',
			'created_at' => '0000-00-00',
			'updated_at' => '0000-00-00'
		]);

		DB::table('oauth_clients')->insert([
			'id' 		=> 'client_one',
			'secret' 	=> bin2hex(random_bytes(30)),
			'name' 		=> 'A normal client',
			'created_at' => '0000-00-00',
			'updated_at' => '0000-00-00'
		]);
		//////////////////////////////////////////
		/// Details
		$details['client_one'] = [
			'database' => [
				'driver' 	=> 'mysql',
				'host'      => '192.168.10.10',
				'database'  => 'formandsystem_client',
				'username'  => 'homestead',
				'password'  => 'secret',
				'charset'   => 'utf8',
				'collation' => 'utf8_unicode_ci',
				'prefix'    => '',
			],
			'image_ftp' => [
				'type' => 'ftp',
				'host' => 'ftp.example.com',
				'username' => '373917-test',
				'password' => 'test1234',
				/** optional config settings */
				'port' => 21,
				// 'root' => '/path/to/root',
				'passive' => true,
				'ssl' => false,
				'timeout' => 30,
			],
			'backup_ftp' =>[
				'type' => 'sftp',
				'host' => 'ftp.example.com',
				'port' => 21,
				'username' => '373917-test',
				'password' => 'test1234',
				// 'privateKey' => 'path/to/or/contents/of/privatekey',
				// 'root' => '/path/to/root',
				'timeout' => 10,
			]
		];


		App\Api\V1\Models\Client::where('id','client_one')->each(function($client) use($details){
			foreach($details['client_one'] as $type => $data)
			$client->details()->save(App\Api\V1\Models\Detail::create([
				'type' => $type,
				'data' => json_encode($data),
			]));
        });

		DB::table('oauth_client_scopes')->insert(
			[
				[
					'client_id' => 'formandsystem',
					'scope_id' => 'client.create',
					'created_at' => '0000-00-00',
					'updated_at' => '0000-00-00'
				],
				[
					'client_id' => 'formandsystem',
					'scope_id' => 'client.delete',
					'created_at' => '0000-00-00',
					'updated_at' => '0000-00-00'
				],
				[
					'client_id' => 'formandsystem',
					'scope_id' => 'client.get',
					'created_at' => '0000-00-00',
					'updated_at' => '0000-00-00'
				]
			]
		);
	}

}
