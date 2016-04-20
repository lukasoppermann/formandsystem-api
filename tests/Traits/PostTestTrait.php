<?php

namespace Lukasoppermann\Testing\Traits;


trait PostTestTrait
{
    /**
     * post new resource
     */
    public function postResource(){
        // POST
        $response = $this->client->request('POST', '/'.$this->resource, [
            'headers' => ['Accept' => 'application/json'],
            'body' => json_encode([
                "data" => $this->resource()->data()
            ])
        ]);
        // GET DATA
        $data = $this->getResponseArray($response)['data'];
        // ASSERTIONS
        $this->assertEquals(self::HTTP_CREATED, $response->getStatusCode());
        $this->assertNotNull($this->model->find($data['id']));
        $this->assertValid($data, $this->resource()->blueprint());
    }
    /**
     * post new resource with relationships
     */
    public function postResourceWithMultipleRelationships(){
        // PREPARE
        $relationshipData = [];
        foreach($this->relationships()as $relationship){
            // get related model
            $relatedModel = "App\Api\V1\Models\\".ucfirst(substr($relationship,0,-1));
            // build relationship data
            $relationshipData[$relationship]['data'] = [
                [
                    'id' => (new $relatedModel)->all()->random(1)->id,
                    'type' => $relationship
                ],
                [
                    'id' => (new $relatedModel)->all()->random(1)->id,
                    'type' => $relationship
                ]
            ];
        }
        // POST
        $response = $this->client->request('POST', '/'.$this->resource, [
            'headers' => ['Accept' => 'application/json'],
            'body' => json_encode([
                "data" => array_merge(
                    $this->resource()->data(),
                    ['relationships' => $relationshipData]
                )
            ])
        ]);
        // GET DATA
        $data = $this->getResponseArray($response)['data'];
        // ASSERTIONS
        $this->assertEquals(self::HTTP_CREATED, $response->getStatusCode());
        $this->assertNotNull($this->model->find($data['id']));
        $this->assertValid($data, $this->resource()->blueprint());
        // ASSERT RELATIONSHIPS
        foreach($this->relationships() as $relationship){
            $this->assertNotNull($this->model->find($data['id'])->{$relationship}->first());
        }
    }
    /**
     * post new resource with one relationship item as object
     */
    public function postResourceWithOneRelationship(){
        // PREPARE
        $relationshipData = [];
        foreach($this->relationships()as $relationship){
            // get related model
            $relatedModel = "App\Api\V1\Models\\".ucfirst(substr($relationship,0,-1));
            // build relationship data
            $relationshipData[$relationship]['data'] = [
                'id' => (new $relatedModel)->all()->random(1)->id,
                'type' => $relationship
            ];
        }
        // POST
        $response = $this->client->request('POST', '/'.$this->resource, [
            'headers' => ['Accept' => 'application/json'],
            'body' => json_encode([
                "data" => array_merge(
                    $this->resource()->data(),
                    ['relationships' => $relationshipData]
                )
            ])
        ]);
        // GET DATA
        $data = $this->getResponseArray($response)['data'];
        // ASSERTIONS
        $this->assertEquals(self::HTTP_CREATED, $response->getStatusCode());
        $this->assertNotNull($this->model->find($data['id']));
        $this->assertValid($data, $this->resource()->blueprint());
        // ASSERT RELATIONSHIPS
        foreach($this->relationships()as $relationship){
            $this->assertNotNull($this->model->find($data['id'])->{$relationship}->first());
        }
    }
    /**
     * post new resource with wrong relationships, that is not allowed
     */
    public function postResourceWithWrongRelationships(){
        if(count($this->relationships()) !== 0){
            // PREPARE
            $relatedModel = "App\Api\V1\Models\\".ucfirst(substr($this->relationships()[0],0,-1));
            // POST
            $response = $this->client->request('POST', '/'.$this->resource, [
                'headers' => ['Accept' => 'application/json'],
                'body' => json_encode([
                    "data" => array_merge(
                        $this->resource()->data(),
                        ['relationships' =>
                            ['wrongRelationship' =>
                                ['data' => [
                                    'id' => (new $relatedModel)->all()->random(1)->id,
                                    'type' => $this->relationships()[0]
                                ]]
                            ]
                        ]
                    )
                ])
            ]);
            // ASSERTIONS
            $this->assertEquals(self::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
        }
    }
    /**
     * post new resource with wrong relationships with wrong type in data
     */
    public function postResourceWithWrongRelationshipTypes(){
        if(count($this->relationships()) !== 0){
            // PREPARE
            $relatedModel = "App\Api\V1\Models\\".ucfirst(substr($this->relationships()[0],0,-1));
            // POST
            $response = $this->client->request('POST', '/'.$this->resource, [
                'headers' => ['Accept' => 'application/json'],
                'body' => json_encode([
                    "data" => array_merge(
                        $this->resource()->data(),
                        ['relationships' =>
                            [$this->relationships()[0] =>
                                ['data' => [
                                    'id' => (new $relatedModel)->all()->random(1)->id,
                                    'type' => 'wrongRelationship'
                                ]]
                            ]
                        ]
                    )
                ])
            ]);
            // ASSERTIONS
            $this->assertEquals(self::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
            // POST RELATIONSHIP ARRAY
            $response = $this->client->request('POST', '/'.$this->resource, [
                'headers' => ['Accept' => 'application/json'],
                'body' => json_encode([
                    "data" => array_merge(
                        $this->resource()->data(),
                        ['relationships' =>
                            [$this->relationships()[0] =>
                                ['data' => [[
                                    'id' => (new $relatedModel)->all()->random(1)->id,
                                    'type' => 'wrongRelationship'
                                ]]]
                            ]
                        ]
                    )
                ])
            ]);
            // ASSERTIONS
            $this->assertEquals(self::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
        }
    }
    /**
     * post new resource with wrong type
     */
    public function postResourceWrongType(){
        // POST
        $response = $this->client->request('POST', '/'.$this->resource, [
            'headers' => ['Accept' => 'application/json'],
            'body' => json_encode([
                "data" => array_merge(
                    $this->resource()->data(),
                    ['type' => 'wrongType']
                )
            ])
        ]);
        // ASSERTIONS
        $this->assertEquals(self::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
        // POST with NO Type
        $data = $this->resource()->data();
        unset($data['type']);
        $response = $this->client->request('POST', '/'.$this->resource, [
            'headers' => ['Accept' => 'application/json'],
            'body' => json_encode([
                "data" => $data
            ])
        ]);
        // ASSERTIONS
        $this->assertEquals(self::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }
    /**
     * post new resource with incomplete data
     */
    public function postResourceIncompleteData(){
        // POST
        $response = $this->client->request('POST', '/'.$this->resource, [
            'headers' => ['Accept' => 'application/json'],
            'body' => json_encode([
                "data" => array_merge(
                    $this->resource()->incomplete()
                )
            ])
        ]);
        // ASSERTIONS
        $this->assertEquals(self::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }
    /**
     * post new resource with additional data
     */
    public function postResourceAdditonalData(){
        // POST
        $response = $this->client->request('POST', '/'.$this->resource, [
            'headers' => ['Accept' => 'application/json'],
            'body' => json_encode([
                "data" => array_merge(
                    array_merge(
                        $this->resource()->data(),
                        ['additonalData' => 'not supported']
                    )
                )
            ])
        ]);
        // ASSERTIONS
        $this->assertEquals(self::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }
    /**
     * post new resource with no body
     */
    public function postResourceNoBody(){
        // POST
        $response = $this->client->request('POST', '/'.$this->resource, [
            'headers' => ['Accept' => 'application/json']
        ]);
        // ASSERTIONS
        $this->assertEquals(self::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }
    /**
     * post new resource with incomplete relationship
     */
    public function postResourceIncompleteRelationship(){
        if(count($this->relationships()) !== 0){
            // PREPARE
            $relatedModel = "App\Api\V1\Models\\".ucfirst(substr($this->relationships()[0],0,-1));
            // POST
            $response = $this->client->request('POST', '/'.$this->resource, [
                'headers' => ['Accept' => 'application/json'],
                'body' => json_encode([
                    "data" => array_merge(
                        $this->resource()->data(),
                        ['relationships' =>
                            [$this->relationships()[0] =>
                                ['data' => [
                                    'id' => (new $relatedModel)->all()->random(1)->id
                                ]]
                            ]
                        ]
                    )
                ])
            ]);
            // ASSERTIONS
            $this->assertEquals(self::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
            // POST For ARRAY
            $response = $this->client->request('POST', '/'.$this->resource, [
                'headers' => ['Accept' => 'application/json'],
                'body' => json_encode([
                    "data" => array_merge(
                        $this->resource()->data(),
                        ['relationships' =>
                            [$this->relationships()[0] =>
                                ['data' => [
                                    ['id' => (new $relatedModel)->all()->random(1)->id]
                                ]]
                            ]
                        ]
                    )
                ])
            ]);
            // ASSERTIONS
            $this->assertEquals(self::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
        }
    }
    //////////////////////////////////////////
    //
    // RELATIONSHIPS
    //
    /**
     * post new resource with wrong relationship
     */
    public function postRelationships(){
        $model = $this->model->first();
        // PREPARE
        $relationshipData = [];
        foreach($this->relationships() as $relationship){
            $model->{$relationship}()->detach();
            $this->assertEquals(count($model->{$relationship}),0);
            // get related model
            $relatedModel = "App\Api\V1\Models\\".ucfirst(substr($relationship,0,-1));
            // build relationship data
            $relationshipData[$relationship] = [
                'id' => (new $relatedModel)->all()->random(1)->id,
                'type' => $relationship
            ];
            // POST
            $response = $this->client->request('POST', '/'.$this->resource.'/'.$model->id.'/relationships/'.$relationship, [
                'headers' => ['Accept' => 'application/json'],
                'body' => json_encode([
                    "data" => array_merge(
                        $relationshipData[$relationship]
                    )
                ])
            ]);
            dd($this->getResponseArray($response));
            // GET DATA
            $data = $this->getResponseArray($response)['data'];
            // ASSERTIONS
            $this->assertEquals(self::HTTP_CREATED, $response->getStatusCode());
            $this->assertNotNull($this->model->find($data['id']));
            $this->assertValid($data, $this->resource()->blueprint());
            // ASSERT RELATIONSHIPS
            foreach($this->relationships()as $relationship){
                $this->assertNotNull($this->model->find($data['id'])->{$relationship}->first());
            }
        }
    }
}
