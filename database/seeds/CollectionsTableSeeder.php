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
		DB::table('collectionables')->truncate();
        // factory('App\Api\V1\Models\Collection', 5)->create();

		App\Api\V1\Models\Collection::first()->each(function($collection){
			for($i = rand(1,4); $i > 0; $i--){
            	$collection->collections()->save(factory('App\Api\V1\Models\Collection')->create());
			}
        });
	}

}
