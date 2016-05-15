<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder
{
    // tables below will be:
    // 1. truncated / deleted
    // 2. seeded
    protected $tables = [
        'oauth_clients',
        'oauth_scopes',
        'oauth_client_scopes',
        'details',
        // 'client_detail',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $connection = app('config')->get('database.default');
        $db = app('config')->get('database.connections.'.$connection);

        Model::unguard();
        if($db['driver'] === 'mysql'){
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        }

        foreach ($this->tables as $table) {
            // empty table
            DB::table($table)->truncate();
        }

        // seed table
        $this->call('ScopeTableSeeder');
        $this->call('ClientTableSeeder');

        if($db['driver'] === 'mysql'){
            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
        }
        Model::reguard();
    }
}
