<?php

class UsersTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
		\DB::table('users')->truncate();
        
		\DB::table('users')->insert(array (
			0 => 
			array (
				'id' => 1,
				'email' => 'oppermann.lukas@gmail.com',
				'password' => '$2y$10$PK4TLdFxbT8p2ROMA/Yehu.xT/FbSAZxRTiu/M1oA/MRyLPZ/wsyG',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
				'service_url' => NULL,
				'service_name' => NULL,
				'service_user' => NULL,
				'service_key' => NULL,
			),
			1 => 
			array (
				'id' => 2,
				'email' => 'lukas@vea.re',
				'password' => '$2y$10$bByrPeE79vO3OFyFmmodK.eVNSZFHV93PjSi6yKTtjUCamHL71ewu',
				'created_at' => '0000-00-00 00:00:00',
				'updated_at' => '0000-00-00 00:00:00',
				'service_url' => 'cms.formandsystem.com',
				'service_name' => 'copra',
				'service_user' => 'root',
				'service_key' => 'root',
			),
		));
	}

}
