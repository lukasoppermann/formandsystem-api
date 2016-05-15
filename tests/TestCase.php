<?php

use Lukasoppermann\Httpstatus\Httpstatuscodes;
use Lukasoppermann\Testing\Traits\TestTrait;
use GuzzleHttp\Client as Guzzle;

class TestCase extends Laravel\Lumen\Testing\TestCase implements Httpstatuscodes
{
    use TestTrait;
    // guzzle client
    protected $client;
    // the tests main model
    protected $model;
    // determin if tokens should be created
    protected $createTokens = true;
    // static init
    protected static $init = false;
    // store tokens globally
    protected $tokens = [];
    // store tokens globally
    protected static $token = false;
    /*
     * SETUP
     */
    public static $inited = false;
    /*
     * before every test class
     */
    public static function setUpBeforeClass(){
        if (app()->environment() === 'production') {
            exit("\33[1;31mNever run tests on Production!\n\n\n");
        }
    }
    /*
     * before every test
     */
    public function setUp()
    {
        parent::setUp();
        // get app instance
        $this->app = $this->createApplication();
        // migrate database
        if( static::$init === false ){
            static::$init = true;
            $this->app->make('Illuminate\Contracts\Console\Kernel')->call('migrate:refresh');
            $this->app->make('Illuminate\Contracts\Console\Kernel')->call('db:seed');
        }
        // set user db for test
        $this->app->make('config')->set('database.connections.user', [
            'driver'    => 'mysql',
            'host'      => '192.168.10.10',
            'database'  => 'formandsystem_client',
            'username'  => 'homestead',
            'password'  => 'secret',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ]);
        // get tokens
        $this->createTokens();
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
        return $app;
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
    /**
     * create oauth access tokens
     *
     * @method createTokens
     *
     * @return void
     */
    public function createTokens(){
        if($this->createTokens === true && static::$token === false){
            // create config
            ////// CMS
            $cms['config'] = [
                'client_id' => 'formandsystem',
                'client_secret' => '5fcaaf78514a024688b35f4f4ad946394cb79e50',
            ];
            $cms['scopes'] = ['client.get','client.post','client.delete'];
            ////// CLIENT
            $client['config'] = [
                'client_id' => 'client_one',
                'client_secret' => '5fcaaf78514a024688b35f4f4ad946394cb79e50',
            ];
            $client['scopes'] = ['content.patch','content.get','content.post','content.delete'];
            ///////////////
            // get tokens
            static::$token['cms'] = $this->tokens['cms'] = $this->getToken($cms['config'], $cms['scopes']);
            static::$token['client'] = $this->tokens['client'] = $this->getToken($client['config'], $client['scopes']);
        }else {
            $this->tokens['cms'] = static::$token['cms'];
            $this->tokens['client'] = static::$token['client'];
        }
    }
    /**
     * request a token via api
     *
     * @method getToken
     *
     * @return string
     */
    public function getToken($config, $scopes){
        $response = $this->client()->post('/tokens', [
            'headers' => ['Accept' => 'application/json'],
            'form_params' => [
                'grant_type'    => 'client_credentials',
                'client_id'     => $config['client_id'],
                'client_secret' => $config['client_secret'],
                'scope'         => implode(',',array_map('trim',$scopes)),
            ]
        ]);
        // get response
        $tokenResponse = $this->getResponse($response, 201);
        // return token
        return $tokenResponse['data']['id'];
    }
    /**
     * get Response
     *
     * @method getResponse
     *
     * @param  [type]      $response [description]
     *
     * @return [type]
     */
    public function getResponse($response, $status = false){
        // get response
        $r = json_decode((string) $response->getBody(), true);
        // check valid response
        if( isset($r['data']) ){
            // check status code
            if($status !== false){
                $this->assertEquals($status, $response->getStatusCode());
            }
            // return response
            return $r;
        }
        // fail test on error
        $this->fail('Error response ['.$r['error']['status_code'].']: '.$r['error']['message']);
    }
    public function client(){
        // Boot up HTTP Client
        return new Guzzle([
            'base_uri' => 'http://api.formandsystem.app',
            'exceptions' => false,
        ]);
    }
}
