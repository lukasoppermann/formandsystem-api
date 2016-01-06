<?php

use Illuminate\Database\Seeder;

class FragmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('fragmentables')->truncate();
        // create content fragments
        factory('App\Api\V1\Models\Fragment', 50)->create();
        // create section fragments
        factory('App\Api\V1\Models\Fragment', 'section', 20)->create();

        // connect sections with content fragments
        App\Api\V1\Models\Fragment::where('type', 'section')->get()->slice(rand(1,5))->each(function($section) {
            // connect this section to 1 to 5 pages
            for( $i = rand(1,5); $i > 0; $i--) {
                App\Api\V1\Models\Page::all()->random()->fragments()->save($section);
            }
            // get content fragments and connect to section
            $fragmentCollection = App\Api\V1\Models\Fragment::where('type', '!=', 'section');
            $fragmentCollection->get()->random(rand(2,5))->each(function($fragment) use ($section){
                $section->fragments()->save($fragment);
            });
        });
    }
}
