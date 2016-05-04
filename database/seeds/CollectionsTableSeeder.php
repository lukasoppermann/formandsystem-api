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
        factory('App\Api\V1\Models\Collection', 5)->create();

		App\Api\V1\Models\Collection::first()->each(function($collection){
            $collection->collections()->save(App\Api\V1\Models\Collection::create([
				'id' => '1234',
		        'name' => 'test test',
		        'slug' => 'test-test',
			]));
        });
	}

}
