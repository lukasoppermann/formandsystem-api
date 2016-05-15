<?php

use Lukasoppermann\Httpstatus\Httpstatuscodes;
use Lukasoppermann\Testing\Traits\TestTrait;
use Illuminate\Support\Facades\Artisan;

class ClientDetailsTest extends TestCase{
    // the tests resource
    protected $resource = 'details';
    /**
     * @method testPostClient
     */
    public function testPostClientDetails(){
        // CALL
        $response = $this->client()->request('POST', '/'.$this->resource, [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$this->tokens['cms'],
            ],
            'body' => json_encode([
                "data" => [
                    "type" => "details",
                    "attributes" => [
                        "type" => "database",
                        "data" => [
                            "driver" => "mysql",
                            "host"   => "127.0.0.1",
                        ]
                    ],
                    "relationships" => [
                        "ownedByClients" => [
                            "data" => [
                                "type"  => "clients",
                                "id"    => "client_to_delete",
                            ]
                        ]
                    ]
                ]
            ])
        ]);
        // GET DATA
        $received = $this->getResponse($response, 201)['data'];
        // ASSERTIONS
        $this->assertEquals($received['attributes']['type'], "database");
    }
    /**
     * @method testDeleteClient
     */
    public function testDeleteClientDetails(){
        // Get client
        $client = $this->getResponse($this->getClientResponse('/clients/client_to_delete', [
            'Authorization' => 'Bearer '.$this->tokens['cms']
        ]), 200)['data'];

        $detail_id = $client['relationships']['details']['data'][key($client['relationships']['details']['data'])]['id'];
        // CALL
        $response = $this->client()->request('DELETE', '/'.$this->resource.'/'.$detail_id, [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer '.$this->tokens['cms'],
            ]
        ]);
        // GET DATA
        $this->assertEquals(self::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}
