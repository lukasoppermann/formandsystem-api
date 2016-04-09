<?php

use Illuminate\Database\Seeder;

class MetadetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory('App\Api\V1\Models\Metadetail', 50)->create();

        App\Api\V1\Models\Page::all()->each(function($page){
            if( rand(0,2) !== 0 ){
                App\Api\V1\Models\Metadetail::all()->random(rand(2,5))->each(function($metadetails) use ($page){
                    $metadetails->pages()->save($page);
                });
            }
        });
    }
}
