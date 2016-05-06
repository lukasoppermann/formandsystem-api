<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    // tables below will be:
    // 1. truncated / deleted
    // 2. seeded
    protected $tables = [
        'collections',
        'pages',
        'fragments',
        'images',
        'metadetails',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        // empty tables
        foreach ($this->tables as $table) {
            DB::table($table)->truncate();
        }
        // seed DB
        factory('App\Api\V1\Models\Collection', 5)->create();
        factory('App\Api\V1\Models\Page', 20)->create();
        factory('App\Api\V1\Models\Metadetail', 50)->create();
        factory('App\Api\V1\Models\Fragment', 50)->create();
        factory('App\Api\V1\Models\Fragment', 'section', 20)->create();
        factory('App\Api\V1\Models\Image', 50)->create();

        // run seeders for relationships
        foreach ($this->tables as $table) {
            $this->call(ucfirst($table).'TableSeeder');
        }

        Model::reguard();
    }
}
