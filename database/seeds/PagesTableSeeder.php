<?php

use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\Api\V1\Models\Page', 20)->create();

        App\Api\V1\Models\Page::all()->each(function($page){
            $i = rand(0,2);
            if( $i === 2 ){
                App\Api\V1\Models\Collection::all()->random(2)->each(function($collection) use ($page){
                    $collection->pages()->save($page);
                });
            }
            if( $i === 1 ){
                App\Api\V1\Models\Collection::all()->random()->pages()->save($page);
            }
        });
    }
}
