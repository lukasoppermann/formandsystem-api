<?php

namespace Lukasoppermann\Testing\Traits;


trait PostTestTrait
{
    /**
     * post new resource
     * @test
     * @group post
     * @group main
     */
    public function postResource(){
        // POST
        $response = $this->client()->request('POST', '/'.$this->resource, [
            'headers' => $this->headers(),
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
     * @test
     * @group test
     * @group main
     */
    public function postResourceWithMultipleRelationships(){
        // PREPARE
        $relationshipData = [];
        foreach($this->relationships() as $relationship){
            // get related model
            $realRelationship = strtolower(str_replace('ownedBy','',$relationship));
            $relatedModel = $this->newModel($realRelationship);
            // build relationship data
            $relationshipData[$relationship]['data'] = [
                [
                    'id' => $relatedModel->all()->random(1)->id,
                    'type' => $realRelationship
                ],
                [
                    'id' => $relatedModel->all()->random(1)->id,
                    'type' => $realRelationship
                ]
            ];
        }
        // POST
        $response = $this->client()->request('POST', '/'.$this->resource, [
            'headers' => $this->headers(),
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
            \LOG::debug('Fix this test');
            // $this->assertNotNull($this->model->find($data['id'])->{$relationship}->first());
        }
    }
    /**
     * post new resource with one relationship item as object
     * @test
     * @group post
     * @group main
     */
    public function postResourceWithOneRelationship(){
        // PREPARE
        $relationshipData = [];
        foreach($this->relationships()as $relationship){
            // get related model
            $relatedModel = $this->newModel($relationship);
            // build relationship data
            $relationshipData[$relationship]['data'] = [
                'id' => $relatedModel->all()->random(1)->id,
                'type' => strtolower(str_replace('ownedBy','',$relationship))
            ];
        }
        // POST
        $response = $this->client()->request('POST', '/'.$this->resource, [
            'headers' => $this->headers(),
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
            \LOG::debug('Fix this test');
            // $this->assertNotNull($this->model->find($data['id'])->{$relationship}->first());
        }
    }
    /**
     * post new resource with wrong relationships, that is not allowed
     * @test
     * @group post
     * @group main
     */
    public function postResourceWithWrongRelationships(){
        if(count($this->relationships()) !== 0){
            // PREPARE
            $relatedModel = $this->newModel($this->relationships()[0]);
            // POST
            $response = $this->client()->request('POST', '/'.$this->resource, [
                'headers' => $this->headers(),
                'body' => json_encode([
                    "data" => array_merge(
                        $this->resource()->data(),
                        ['relationships' =>
                            ['wrongRelationship' =>
                                ['data' => [
                                    'id' => $relatedModel->all()->random(1)->id,
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
     * @test
     * @group post
     * @group main
     */
    public function postResourceWithWrongRelationshipTypes(){
        if(count($this->relationships()) !== 0){
            // PREPARE
            $relatedModel = $this->newModel($this->relationships()[0]);
            // POST
            $response = $this->client()->request('POST', '/'.$this->resource, [
                'headers' => $this->headers(),
                'body' => json_encode([
                    "data" => array_merge(
                        $this->resource()->data(),
                        ['relationships' =>
                            [$this->relationships()[0] =>
                                ['data' => [
                                    'id' => $relatedModel->all()->random(1)->id,
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
            $response = $this->client()->request('POST', '/'.$this->resource, [
                'headers' => $this->headers(),
                'body' => json_encode([
                    "data" => array_merge(
                        $this->resource()->data(),
                        ['relationships' =>
                            [$this->relationships()[0] =>
                                ['data' => [[
                                    'id' => $relatedModel->all()->random(1)->id,
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
     * @test
     * @group post
     * @group main
     */
    public function postResourceWrongType(){
        // POST
        $response = $this->client()->request('POST', '/'.$this->resource, [
            'headers' => $this->headers(),
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
        $response = $this->client()->request('POST', '/'.$this->resource, [
            'headers' => $this->headers(),
            'body' => json_encode([
                "data" => $data
            ])
        ]);
        // ASSERTIONS
        $this->assertEquals(self::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }
    /**
     * post new resource with incomplete data
     * @test
     * @group post
     * @group main
     */
    public function postResourceIncompleteData(){
        // POST
        $response = $this->client()->request('POST', '/'.$this->resource, [
            'headers' => $this->headers(),
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
     * @test
     * @group post
     * @group main
     */
    public function postResourceAdditonalData(){
        // POST
        $response = $this->client()->request('POST', '/'.$this->resource, [
            'headers' => $this->headers(),
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
     * @test
     * @group post
     * @group main
     */
    public function postResourceNoBody(){
        // POST
        $response = $this->client()->request('POST', '/'.$this->resource, [
            'headers' => $this->headers(),
        ]);
        // ASSERTIONS
        $this->assertEquals(self::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }
    /**
     * post new resource with incomplete relationship
     * @test
     * @group post
     * @group main
     */
    public function postResourceIncompleteRelationship(){
        if(count($this->relationships()) !== 0){
            // PREPARE
            // $relatedModel = "App\Api\V1\Models\\".ucfirst(substr($this->relationships()[0],0,-1));
            $relatedModel = $this->newModel($this->relationships()[0]);
            // POST
            $response = $this->client()->request('POST', '/'.$this->resource, [
                'headers' => $this->headers(),
                'body' => json_encode([
                    "data" => array_merge(
                        $this->resource()->data(),
                        ['relationships' =>
                            [$this->relationships()[0] =>
                                ['data' => [
                                    'id' => $relatedModel->all()->random(1)->id
                                ]]
                            ]
                        ]
                    )
                ])
            ]);
            // ASSERTIONS
            $this->assertEquals(self::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
            // POST For ARRAY
            $response = $this->client()->request('POST', '/'.$this->resource, [
                'headers' => $this->headers(),
                'body' => json_encode([
                    "data" => array_merge(
                        $this->resource()->data(),
                        ['relationships' =>
                            [$this->relationships()[0] =>
                                ['data' => [
                                    ['id' => $relatedModel->all()->random(1)->id]
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
     * post relationship
     * @test
     * @group post
     * @group rel
     */
    public function postRelationships(){
        $model = $this->model->first();
        // PREPARE
        foreach($this->relationships() as $relationship){
            // get related model
            $relatedModel = $this->newModel($relationship);
            // delete all relationships for testing
            $model->{$relationship}()->detach();
            $this->assertEquals(count($model->{$relationship}),0);
            // POST WITH SINGLE ITEM
            $response = $this->client()->request('POST', '/'.$this->resource.'/'.$model->id.'/relationships/'.$relationship, [
                'headers' => $this->headers(),
                'body' => json_encode([
                    "data" => [
                        'id' => $relatedModel->all()->random(1)->id,
                        'type' => strtolower(str_replace('ownedBy','',$relationship))
                    ]
                ])
            ]);
            // ASSERTIONS
            $this->assertEquals(self::HTTP_NO_CONTENT, $response->getStatusCode());
            $this->assertNotNull($this->model->find($model->id)->{$relationship}->first());
            // delete all relationships for testing
            $model->{$relationship}()->detach();
            $this->assertEquals(count($model->{$relationship}),0);
            // POST WITH SINGLE ITEM
            $response = $this->client()->request('POST', '/'.$this->resource.'/'.$model->id.'/relationships/'.$relationship, [
                'headers' => $this->headers(),
                'body' => json_encode([
                    "data" => [[
                        'id' => $relatedModel->all()->random(1)->id,
                        'type' => strtolower(str_replace('ownedBy','',$relationship))
                    ]]
                ])
            ]);
            // ASSERTIONS
            $this->assertEquals(self::HTTP_NO_CONTENT, $response->getStatusCode());
            $this->assertNotNull($this->model->find($model->id)->{$relationship}->first());
        }
    }
    /**
     * post relationship with wrong data
     * @test
     * @group post
     * @group rel
     */
    public function postRelationshipsWrongRelationshipData(){
        $model = $this->model->first();
        // PREPARE
        foreach($this->relationships() as $relationship){
            // delete all relationships for testing
            $model->{$relationship}()->detach();
            $this->assertEquals(count($model->{$relationship}),0);
            // get related model
            $relatedModel = $this->newModel($relationship);
            // POST
            $response = $this->client()->request('POST', '/'.$this->resource.'/'.$model->id.'/relationships/'.$relationship, [
                'headers' => $this->headers(),
                'body' => json_encode([
                    "data" => [
                        'id' => $relatedModel->all()->random(1)->id,
                        'type' => 'wrongType'
                    ]
                ])
            ]);
            // ASSERTIONS
            $this->assertEquals(self::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
            $this->assertEquals($model->find($model->id)->{$relationship}->count(),0);
            // POST
            $response = $this->client()->request('POST', '/'.$this->resource.'/'.$model->id.'/relationships/'.$relationship, [
                'headers' => $this->headers(),
                'body' => json_encode([
                    "data" => [
                        'id' => 'wrongId',
                        'type' => strtolower(str_replace('ownedBy','',$relationship))
                    ]
                ])
            ]);
            // ASSERTIONS
            $this->assertEquals(self::HTTP_BAD_REQUEST, $response->getStatusCode());
            $this->assertEquals($model->find($model->id)->{$relationship}->count(),0);
        }
    }
    /**
     * post relationship to wrong resource
     * @test
     * @group post
     * @group rel
     */
    public function postRelationshipsWithWrongResourceId(){
        foreach($this->relationships() as $relationship){
            // get related model
            $relatedModel = $this->newModel($relationship);
            // POST
            $response = $this->client()->request('POST', '/'.$this->resource.'/1/relationships/'.$relationship, [
                'headers' => $this->headers(),
                'body' => json_encode([
                    "data" => [
                        'id' => $relatedModel->all()->random(1)->id,
                        'type' => strtolower(str_replace('ownedBy','',$relationship))
                    ]
                ])
            ]);
            // ASSERTIONS
            $this->assertEquals(self::HTTP_NOT_FOUND, $response->getStatusCode());
        }
    }
    /**
     * post relationship to wrong relationship
     * @test
     * @group post
     * @group rel
     */
    public function postRelationshipsToWrongUrl(){
        $model = $this->model->first();
        foreach($this->relationships() as $relationship){
            // get related model
            $relatedModel = $this->newModel($relationship);
            // POST
            $response = $this->client()->request('POST', '/'.$this->resource.'/'.$model->id.'/relationships/wrongRelationship', [
                'headers' => $this->headers(),
                'body' => json_encode([
                    "data" => [
                        'id' => $relatedModel->all()->random(1)->id,
                        'type' => strtolower(str_replace('ownedBy','',$relationship))
                    ]
                ])
            ]);
            // ASSERTIONS
            $this->assertEquals(self::HTTP_NOT_FOUND, $response->getStatusCode());
        }
    }

// END OF CLASS
}
