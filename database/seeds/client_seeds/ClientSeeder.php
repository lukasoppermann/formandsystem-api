<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ClientSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        // empty all tables
        DB::table('collections')->truncate();
        DB::table('pages')->truncate();
        DB::table('fragments')->truncate();
        DB::table('images')->truncate();
        DB::table('metadetails')->truncate();
        // seed DB
        factory('App\Api\V1\Models\Collection', 5)->create();
        factory('App\Api\V1\Models\Page', 20)->create();
        factory('App\Api\V1\Models\Metadetail', 50)->create();
        factory('App\Api\V1\Models\Fragment', 50)->create();
        factory('App\Api\V1\Models\Fragment', 'section', 20)->create();
        factory('App\Api\V1\Models\Image', 50)->create();
        // Add relationships
        $this->call('CollectionsTableSeeder');
        $this->call('PagesTableSeeder');
        $this->call('MetadetailsTableSeeder');
        $this->call('FragmentsTableSeeder');
        $this->call('ImagesTableSeeder');
        // rest DB
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        Model::reguard();
    }
}
