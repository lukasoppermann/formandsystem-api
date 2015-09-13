<?php

use Illuminate\Database\Seeder;

class StreamsTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
        factory('App\Api\V1\Models\Streams', 50)->create();
	}

}
