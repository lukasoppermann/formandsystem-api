<?php

use Lukasoppermann\Httpstatus\Httpstatuscodes;
use Lukasoppermann\Testing\Traits\TestTrait;
use Lukasoppermann\Testing\Traits\ValidationTrait;
use Lukasoppermann\Testing\BaseTests\GetTest;
use Lukasoppermann\Testing\BaseTests\PostTest;
use Lukasoppermann\Testing\BaseTests\PatchTest;
use Lukasoppermann\Testing\BaseTests\DeleteTest;
use Illuminate\Support\Facades\Artisan as Artisan;

class TestCase extends Laravel\Lumen\Testing\TestCase implements Httpstatuscodes
{
    use TestTrait;
    use ValidationTrait;
    use GetTest;
    use PostTest;
    use PatchTest;
    use DeleteTest;
    // resource objects
    protected $resourceObjects = [
        'Metadetail',
        'Page',
        'Collection',
        'Fragment',
        'Image'
    ];
    // guzzle client
    protected $client;
    // the tests main model
    protected $model;
    // variable to ensure seeding only happens once
    protected static $db_inited = false;
    /*
     * SETUP
     */
    public function setUp()
    {
        // kill tests on production
        if (app()->environment() === 'production') {
            exit("\33[1;31mNever run tests on Production!\n\n\n");
        }
        // run setup
        parent::setUp();
        // RUN migrations
        // $this->initDb();
        // Boot up HTTP Client
        $this->initHttpClient();
        // Create resources
        $this->initResources();
        // init model for current resource
        $this->initModel();

    }
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';
        app('config')->set('database.default', 'sqlite');

        return $app;

    }
    /**
     * run db migrations
     *
     * @method dbInit
     *
     * @return void
     */
    protected function initDb(){
        // run migrations if they haven't been run yet
        if (!static::$db_inited) {
             static::$db_inited = true;
             Artisan::call('migrate:refresh');
             Artisan::call('db:seed');
        }
    }
    /**
     * create a guzzle client
     *
     * @method initHttpClient
     *
     * @return void
     */
    protected function initHttpClient(){
        $this->client = new GuzzleHttp\Client([
            'base_uri' => 'http://api.formandsystem.app',
            'exceptions' => false,
        ]);
    }
    /**
     * create resource objects
     *
     * @method initResources
     *
     * @return void
     */
    protected function initResources(){
        // init resources
        foreach($this->resourceObjects as $resource){
            $Resourceclass = 'Lukasoppermann\Testing\Resources\\'.$resource.'Resource';
            $this->resources[strtolower($resource).'s'] = new $Resourceclass;
        }
    }
    /**
     * create model fpr current resource
     *
     * @method initModel
     *
     * @return void
     */
    public function initModel(){
        // init Model
        $model = "App\Api\V1\Models\\".ucfirst(substr($this->resource,0,-1));
        $this->model = new $model;
    }
}
