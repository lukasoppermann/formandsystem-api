<?php

use Illuminate\Database\Seeder;

class CollectionsTableSeeder extends Seeder {

	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run()
	{
        factory('App\Api\V1\Models\Collection', 50)->create();
	}

}
