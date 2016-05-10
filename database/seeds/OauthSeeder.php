<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class OauthSeeder extends Seeder
{
    // tables below will be:
    // 1. truncated / deleted
    // 2. seeded
    protected $tables = [
        'oauth_clients',
        'oauth_scopes',
        'oauth_client_scopes',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        foreach ($this->tables as $table) {
            // empty table
            DB::table($table)->truncate();
        }

        // seed table
        $this->call('ScopeTableSeeder');
        $this->call('CmsUser');

        Model::reguard();
    }
}
