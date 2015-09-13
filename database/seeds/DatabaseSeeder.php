<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    protected $truncate = ['streams'];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        foreach($this->truncate as $table){
            DB::table($table)->truncate();
        }

        $this->call('StreamsTableSeeder');

        Model::reguard();
    }
}
