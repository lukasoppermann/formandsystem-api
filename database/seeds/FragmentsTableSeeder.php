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
        factory('App\Api\V1\Models\Fragment', 50)->create();
    }
}
