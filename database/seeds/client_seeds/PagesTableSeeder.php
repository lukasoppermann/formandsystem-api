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
        // Connect to pages

        App\Api\V1\Models\Page::all()->each(function($page){
            $i = rand(0,2);
            if( $i === 2 ){
                $page->pages()->save(App\Api\V1\Models\Page::all()->random(1));
                $page->pages()->save(App\Api\V1\Models\Page::all()->random(1));
                App\Api\V1\Models\Collection::all()->random(2)->each(function($collection) use ($page){
                    $collection->pages()->save($page);
                });
            }
            if( $i === 1 ){
                $page->collections()->save(factory('App\Api\V1\Models\Collection')->create());
                App\Api\V1\Models\Collection::all()->random()->pages()->save($page);
            }
        });
    }
}
