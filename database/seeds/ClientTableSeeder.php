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
			'secret' 	=> '5fcaaf78514a024688b35f4f4ad946394cb79e50',
			'name' 		=> 'Form&System',
			'created_at' => '0000-00-00',
			'updated_at' => '0000-00-00'
		]);

		DB::table('oauth_clients')->insert([
			'id' 		=> 'client_one',
			'secret' 	=> '5fcaaf78514a024688b35f4f4ad946394cb79e50',
			'name' 		=> 'A normal client',
			'created_at' => '0000-00-00',
			'updated_at' => '0000-00-00'
		]);

		DB::table('oauth_clients')->insert([
			'id' 		=> 'client_to_delete',
			'secret' 	=> '5fcaaf78514a024688b35f4f4ad946394cb79e50',
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
			'ftp_image' => [
				'type' => 'ftp',
				'host' => 'ftp.formandsystem.com',
				'username' => '373917-test',
				'password' => 'test1234',
				/** optional config settings */
				'port' => 21,
				// 'root' => '/path/to/root',
				'passive' => true,
				'ssl' => false,
				// 'timeout' => 30,
			],
			'ftp_backup' =>[
				'type' => 'sftp',
				'host' => 'ftp.formandsystem.com',
				'username' => '373917-test',
				'password' => 'test1234',
				// 'privateKey' => 'path/to/or/contents/of/privatekey',
				// 'root' => '/path/to/root',
				// 'timeout' => 10,
			]
		];

		$details['client_to_delete'] = [
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
			'ftp_image' => [
				'type' => 'ftp',
				'host' => 'ftp.formandsystem.com',
				'username' => '373917-test',
				'password' => 'test1234',
				/** optional config settings */
				'port' => 21,
				// 'root' => '/path/to/root',
				'passive' => true,
				'ssl' => false,
				'timeout' => 30,
			],
			'ftp_backup' =>[
				'type' => 'sftp',
				'host' => 'ftp.formandsystem.com',
				'port' => 21,
				'username' => '373917-test',
				'password' => 'test1234',
				// 'privateKey' => 'path/to/or/contents/of/privatekey',
				// 'root' => '/path/to/root',
				'timeout' => 10,
			]
		];

		App\Api\V1\Models\Client::where('id','client_one')->each(function($client) use($details){
			foreach($details['client_one'] as $type => $data){
				$detail = App\Api\V1\Models\Detail::create([
					'type' => $type,
					'data' => json_encode($data),
				]);

				$detail->ownedByClients()->attach($client->id);
			}
        });

		App\Api\V1\Models\Client::where('id','client_to_delete')->each(function($client) use($details){
			foreach($details['client_to_delete'] as $type => $data){
				$detail = App\Api\V1\Models\Detail::create([
					'type' => $type,
					'data' => json_encode($data),
				]);
				$detail->ownedByClients()->attach($client->id);
			}
		});

		DB::table('oauth_client_scopes')->insert(
			[
				[
					'client_id' => 'formandsystem',
					'scope_id' => 'client.post',
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
				],
				[
					'client_id' => 'client_one',
					'scope_id' => 'content.post',
					'created_at' => '0000-00-00',
					'updated_at' => '0000-00-00'
				],
				[
					'client_id' => 'client_one',
					'scope_id' => 'content.delete',
					'created_at' => '0000-00-00',
					'updated_at' => '0000-00-00'
				],
				[
					'client_id' => 'client_one',
					'scope_id' => 'content.get',
					'created_at' => '0000-00-00',
					'updated_at' => '0000-00-00'
				],
				[
					'client_id' => 'client_one',
					'scope_id' => 'content.patch',
					'created_at' => '0000-00-00',
					'updated_at' => '0000-00-00'
				],
			]
		);
	}

}
