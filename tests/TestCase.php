<?php

use Lukasoppermann\Httpstatus\Httpstatuscodes;
use Lukasoppermann\Testing\Traits\TestTrait;
use Lukasoppermann\Testing\BaseTests\GetTest;
use Lukasoppermann\Testing\BaseTests\PostTest;
use Lukasoppermann\Testing\BaseTests\PatchTest;
use Lukasoppermann\Testing\BaseTests\DeleteTest;
use Lukasoppermann\Testing\Resources\Collection;
use Lukasoppermann\Testing\Resources\Page;
use Illuminate\Support\Facades\Artisan as Artisan;

class TestCase extends Laravel\Lumen\Testing\TestCase implements Httpstatuscodes
{
    use TestTrait;
    use GetTest;
    use PostTest;
    use PatchTest;
    use DeleteTest;
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
        parent::setUp();
        // run migrations if they haven't been run yet
        if (!static::$db_inited) {
             static::$db_inited = true;
             static::initDB();
        }
        // init guzzle
        $this->client = new GuzzleHttp\Client([
            'base_uri' => 'http://api.formandsystem.app',
            'exceptions' => false,
        ]);
        // init resources
        $this->resources = [
            'collections' => (new Collection)->expected(),
            'collections_post' => (new Collection)->post(),
            'collections_post_incomplete' => (new Collection)->post_incomplete(),
            'pages' => (new Page)->expected()
        ];
        // init Model
        $model = "App\Api\V1\Models\\".ucfirst(substr($this->resource,0,-1));
        $this->model = new $model;
    }
    /*
     * run migration & seeding before tests
     */
    protected static function initDB()
    {
        Artisan::call('migrate:refresh');
        Artisan::call('db:seed');
    }
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }
    /**
     * Decode json response to array
     *
     * @return array
     */
    public function getResponseArray($response)
    {
        return json_decode((string) $response->getBody(), true);
    }
    /**
     * Decode json response to array
     *
     * @return array
     */
    public function assertArray($expected, $actual)
    {
        $specialValues = ['is_int', 'is_string'];

        foreach($expected as $key => $value){
            if( !array_key_exists($key, $actual) ){
                return false;
            }
            if( $value === 'is_int' ){
                return is_int($actual[$key]);
            }
            if( $value === 'is_string' ){
                return is_string($value === $actual[$key]);
            }
            if( !in_array($value, $specialValues) ){
                return ($value === $actual[$key]);
            }
        }

        $this->assertTrue($passes);
    }
    /**
     * get the response from server
     */
    public function getClientResponse($url, $headers = [])
    {
        return $this->client->get($url, [
            'headers' => array_merge([
                'Accept' => 'application/json',
            ], $headers),
        ]);
    }

    /**
     * test error response
     */
    public function checkErrorResponse($response, $errorType){
        $received = $this->getResponseArray($response);
        // check status code
        $this->assertEquals(constant("self::$errorType"), $response->getStatusCode());
        // check for correct structure
        $expected = [
            'error' => [
                'message' => 'string',
                'status_code' => 'integer|in:'.constant("self::$errorType")
            ]
        ];

        $this->assertValidArray($expected, $received);
    }
    /**
     * test for pagination
     */
    public function isPaginated($response){
        $received = $this->getResponseArray($response)['meta'];
        $expected = [
            'pagination' => [
                'total' => 'int',
                'count' => 'int',
                "per_page" => 'int',
                "current_page" => 'in:1',
                "total_pages" => 'int',
            ]
        ];

        $this->assertValidArray($expected, $received);
    }
    /*
     * @test
     */
    protected function getNotFound($url){
        $response = $this->getClientResponse($url);
        // ASSERTIONS
        $this->checkErrorResponse($response, 'HTTP_NOT_FOUND');
    }
    /*
     * prepare data as headers & body for request
     */
    protected function prepData($data = []){
        $output['headers'] = $data;
        $output['body'] = null;
        if(isset($data['headers']) || !isset($data['body']))
        {
            $output['headers'] = isset($data['headers']) ? $data['headers'] : [];
            $output['body'] = isset($data['body']) ? $data['body'] : null;
        }

        return $output;
    }
}
