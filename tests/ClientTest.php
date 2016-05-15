<?php

use Lukasoppermann\Httpstatus\Httpstatuscodes;
use Lukasoppermann\Testing\Traits\TestTrait;
use Illuminate\Support\Facades\Artisan;

class ClientTest extends TestCase{
    // the tests resource
    protected $resource = 'clients';
    /**
     * @method testGetClient
     */
    public function testGetClient(){
        // CALL
        $response = $this->getClientResponse('/'.$this->resource.'/client_to_delete', [
            'Authorization' => 'Bearer '.$this->tokens['cms']
        ]);
        // GET DATA
        $received = $this->getResponse($response, 200)['data'];
        // ASSERTIONS
        $this->assertEquals('client_to_delete', $received['id']);
    }
    /**
     * @method testPostClient
     */
    public function testPostClient(){
        // CALL
        $response = $this->client()->request('POST', '/'.$this->resource, [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$this->tokens['cms'],
            ],
            'body' => json_encode([
                "data" => [
                    "type" => "clients",
                    "attributes" => [
                        "name" => "client_name",
                        "scopes" => "content.get, content.post"
                    ]
                ]
            ])
        ]);
        // GET DATA
        $received = $this->getResponse($response, 201)['data'];
        // ASSERTIONS
        $this->assertEquals($received['attributes']['name'], "client_name");
    }
    /**
     * @method testDeleteClient
     */
    public function testDeleteClient(){
        // CALL
        $response = $this->client()->request('DELETE', '/'.$this->resource.'/client_to_delete', [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$this->tokens['cms'],
            ]
        ]);
        // GET DATA
        $this->assertEquals(self::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}
