<?php

use Formandsystemapi\Models\Content;

class PagesTest extends ApiTester {

	public function setUp()
	{
    parent::setUp();

    Session::start();

		$db = array(
			'driver'    => 'sqlite',
			'database' => storage_path().'/testing-database.sqlite',
			'prefix'    => '',
		);

		Config::set("database.connections.user", $db);

    $path = Config::get('database.connections.user.database');

    if (!file_exists($path) && is_dir(dirname($path))) {
        touch($path);
    }

		Artisan::call('migrate', ['--path' => 'database/testing/migrations', '--database' => 'user' ]);
		Artisan::call('db:seed', ['--class' => 'TestDatabaseSeeder', '--database' => 'user' ]);

    // Enable filters
    // Route::enableFilters();
	}

	/** @test */
	public function it_fetches_pages()
	{

		$req = Mockery::mock('Formandsystemapi\Http\Requests\Pages\getPagesRequest');

		$this->action('GET', 'PagesApiController@index', [$req]);

		$this->assertResponseOK();
	}

	/**
	 * return data for DB seeding
	 *
	 * @method getStub
	 */
	protected function getStub()
	{
		return [
			'status' 			=> 1,
			'language' 		=> $this->fake->randomElement(['de','en']),
			'article_id' 	=> $this->fake->unique()->randomDigitNotNull(),
			'data' 				=> json_encode([]),
			'tags' 				=> implode(',',$this->fake->words($this->fake->randomNumber(1))),
			'menu_label' 	=> $this->fake->word(),
			'link' 				=> $this->fake->word(),
			'parent_id' 	=> 0,
			'position' 		=> 0
		];
	}

}
