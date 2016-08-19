<?php

use Lukasoppermann\Httpstatus\Httpstatuscodes;
use Lukasoppermann\Testing\Traits\CallTrait;
use App\Api\V1\Models\Client;
use Laravel\Lumen\Testing\Concerns\MakesHttpRequests;

class ClientTest extends TestCase{

    use MakesHttpRequests;

    protected $resource = 'clients';

    public function setUp()
    {
        parent::setUp();
        $this->withoutMiddleware();
    }

    /**
     * @method testGetClient
     * @group bug
     */
    public function testGetClient(){
        $client = factory(Client::class)->make();
        // // CALL
        $response = $this->getCall('/'.$this->resource.'/client_to_delete', [
            'Authorization' => 'Bearer 1234'
        ]);
        // GET DATA
        $received = $this->getResponse($response)['data'];
        // ASSERTIONS
        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('client_to_delete', $received['id']);
    }
}
