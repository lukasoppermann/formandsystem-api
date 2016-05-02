<?php

namespace Lukasoppermann\Testing\Traits;

trait PatchTestTrait
{
    /*
     * patch the main resource by id
     * @test
     * @group patch
     * @group main
     */
    public function testPatchResourceById(){
        // PREPARE
        $model = $this->model->first();
        // PATCH
        $response = $this->client->request('PATCH', '/'.$this->resource.'/'.$model->id, [
            'headers' => ['Accept' => 'application/json'],
            'body' => json_encode([
                "data" => array_merge(
                    $this->resource()->incomplete(),
                    ['id' => $model->id]
                )
            ])
        ]);
        $data = $this->getResponseArray($response)['data'];
        // ASSERTIONS
        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());
        $this->assertValid($data, $this->resource()->blueprint());
    }
    /*
     * softdelete the main resource by id
     * @test
     * @group patch
     * @group main
     */
    public function testPatchSoftdeleteResourceById(){
        // PREPARE
        $model = $this->model->first();
        if($model->isSoftdeleting()){
            // prepare data
            $data = $this->resource()->incomplete();
            $data['id'] = $model->id;
            $data['attributes']['is_trashed'] = true;
            // TEST BEFORE REQUEST
            $this->assertTrue($model->deleted_at === null);
            // PATCH
            $response = $this->client->request('PATCH', '/'.$this->resource.'/'.$model->id, [
                'headers' => ['Accept' => 'application/json'],
                'body' => json_encode(["data" => $data])
            ]);
            $data = $this->getResponseArray($response)['data'];
            // ASSERTIONS
            $this->assertEquals(self::HTTP_OK, $response->getStatusCode());
            $this->assertValid($data, $this->resource()->blueprint());
            $this->assertNotNull($this->model->findWithTrashed($model->id)->deleted_at);
        }
    }
    /*
     * softdelete the main resource by id
     * @test
     * @group patch
     * @group main
     */
    public function testPatchRestoreResourceById(){
        // PREPARE
        $model = $this->model->first();
        if($model->isSoftdeleting()){
            // prepare data
            $data = $this->resource()->incomplete();
            $data['id'] = $model->id;
            $data['attributes']['is_trashed'] = false;
            $model->delete();
            // TEST BEFORE REQUEST
            $this->assertNotNull($this->model->findWithTrashed($model->id)->deleted_at);
            // PATCH
            $response = $this->client->request('PATCH', '/'.$this->resource.'/'.$model->id, [
                'headers' => ['Accept' => 'application/json'],
                'body' => json_encode(["data" => $data])
            ]);
            $data = $this->getResponseArray($response)['data'];
            // ASSERTIONS
            $this->assertEquals(self::HTTP_OK, $response->getStatusCode());
            $this->assertValid($data, $this->resource()->blueprint());
            $this->assertNull($this->model->findWithTrashed($model->id)->deleted_at);
        }
    }
    /*
     * patch the main resource by wrong id
     * @test
     * @group patch
     * @group main
     */
    public function testPatchResourceWrongId(){
        // PATCH
        $response = $this->client->request('PATCH', '/'.$this->resource.'/1', [
            'headers' => ['Accept' => 'application/json'],
            'body' => json_encode([
                "data" => array_merge(
                    $this->resource()->incomplete(),
                    ['id' => $this->model->first()->id]
                )
            ])
        ]);
        // ASSERTIONS
        $this->assertEquals(self::HTTP_NOT_FOUND, $response->getStatusCode());
    }
    /*
     * patch the main resource by id
     * @test
     * @group patch
     * @group main
     */
    public function testPatchResourceWrongType(){
        // PATCH
        $response = $this->client->request('PATCH', '/wrongResource/1', [
            'headers' => ['Accept' => 'application/json'],
            'body' => json_encode([
                "data" => array_merge(
                    $this->resource()->incomplete(),
                    ['id' => '1']
                )
            ])
        ]);
        // ASSERTIONS
        $this->assertEquals(self::HTTP_NOT_FOUND, $response->getStatusCode());
    }
    /*
     * patch the main resource by id without attributes
     * @test
     * @group patch
     * @group main
     */
    public function testPatchResourceByIdWithoutAttributes(){
        // PATCH
        $response = $this->client->request('PATCH', '/'.$this->resource.'/'.$this->model->first()->id, [
            'headers' => ['Accept' => 'application/json'],
            'body' => json_encode([
                "data" => [
                    'id' => $this->model->first()->id,
                    'type' => $this->resource
                ]
            ])
        ]);
        // ASSERTIONS
        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());
    }
    /*
     * patch the main resource by id without body
     * @test
     * @group patch
     * @group main
     */
    public function testPatchResourceByIdNoBody(){
        // PATCH
        $response = $this->client->request('PATCH', '/'.$this->resource.'/'.$this->model->first()->id, [
            'headers' => ['Accept' => 'application/json']
        ]);
        // ASSERTIONS
        $this->assertEquals(self::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }
    /*
     * patch the main resource by id with wrong body
     * @test
     * @group patch
     * @group main
     */
    public function testPatchResourceIncompleteData(){
        // PATCH
        $response = $this->client->request('PATCH', '/'.$this->resource.'/'.$this->model->first()->id, [
            'headers' => ['Accept' => 'application/json'],
            'body' => json_encode([
                "data" => array_merge(
                    $this->resource()->incomplete()
                )
            ])
        ]);
        // ASSERTIONS
        $this->assertEquals(self::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
        // PREPARE FOR NO TYPE
        $data = array_merge(
            $this->resource()->incomplete(),
            ['id' => $this->model->first()->id]
        );
        unset($data['type']);
        // PATCH
        $response = $this->client->request('PATCH', '/'.$this->resource.'/'.$this->model->first()->id, [
            'headers' => ['Accept' => 'application/json'],
            'body' => json_encode([
                "data" => $data
            ])
        ]);
        // ASSERTIONS
        $this->assertEquals(self::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }
    /**
     * patch new resource with relationships
     * @test
     * @group patch
     * @group main
     */
    public function testPatchResourceWithMultipleRelationships(){
        // PREPARE
        $model = $this->model->first();
        $relationshipData = [];
        foreach($this->relationships() as $relationship){
            // remove related
            $model->{$relationship}()->detach();
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
        $response = $this->client->request('PATCH', '/'.$this->resource.'/'.$model->id, [
            'headers' => ['Accept' => 'application/json'],
            'body' => json_encode([
                "data" => array_merge(
                    $this->resource()->incomplete(),
                    ['id' => $model->id],
                    ['relationships' => $relationshipData]
                )
            ])
        ]);
        // GET DATA
        $data = $this->getResponseArray($response)['data'];
        // ASSERTIONS
        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());
        $this->assertValid($data, $this->resource()->blueprint());
        // ASSERT RELATIONSHIPS
        foreach($this->relationships() as $relationship){
            $this->assertEquals(2,$this->model->find($model->id)->{$relationship}->count());
        }
    }
    /**
     * patch resource with one relationship item as object
     * @test
     * @group patch
     * @group main
     */
    public function testPatchResourceWithOneRelationship(){
        // PREPARE
        $model = $this->model->first();
        $relationshipData = [];
        foreach($this->relationships() as $relationship){
            // get related model
            $relatedModel = "App\Api\V1\Models\\".ucfirst(substr($relationship,0,-1));
            // build relationship data
            $relationshipData[$relationship]['data'] = [
                'id' => (new $relatedModel)->all()->random(1)->id,
                'type' => $relationship
            ];
        }
        // POST
        $response = $this->client->request('PATCH', '/'.$this->resource.'/'.$model->id, [
            'headers' => ['Accept' => 'application/json'],
            'body' => json_encode([
                "data" => array_merge(
                    ['id' => $model->id],
                    $this->resource()->incomplete(),
                    ['relationships' => $relationshipData]
                )
            ])
        ]);
        // GET DATA
        $data = $this->getResponseArray($response)['data'];
        // ASSERTIONS
        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());
        $this->assertValid($data, $this->resource()->blueprint());
        // ASSERT RELATIONSHIPS
        foreach($this->relationships()as $relationship){
            $this->assertNotNull($this->model->find($data['id'])->{$relationship}->first());
        }
    }
    /**
     * patch resource with wrong relationships, that is not allowed
     * @test
     * @group patch
     * @group main
     */
    public function testPatchResourceWithWrongRelationships(){
        // PREPARE
        $model = $this->model->first();
        if(count($this->relationships()) !== 0){
            // PREPARE
            $relatedModel = "App\Api\V1\Models\\".ucfirst(substr($this->relationships()[0],0,-1));
            // POST
            $response = $this->client->request('PATCH', '/'.$this->resource.'/'.$model->id, [
                'headers' => ['Accept' => 'application/json'],
                'body' => json_encode([
                    "data" => array_merge(
                        ['id' => $model->id],
                        $this->resource()->incomplete(),
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
     * patch resource with wrong relationships with wrong type in data
     * @test
     * @group patch
     * @group main
     */
    public function testPatchResourceWithWrongRelationshipTypes(){
        // PREPARE
        $model = $this->model->first();
        if(count($this->relationships()) !== 0){
            // PREPARE
            $relatedModel = "App\Api\V1\Models\\".ucfirst(substr($this->relationships()[0],0,-1));
            // POST
            $response = $this->client->request('PATCH', '/'.$this->resource.'/'.$model->id, [
                'headers' => ['Accept' => 'application/json'],
                'body' => json_encode([
                    "data" => array_merge(
                        ['id' => $model->id],
                        $this->resource()->incomplete(),
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
                        $this->resource()->incomplete(),
                        ['id' => $model->id],
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
    //////////////////////////////////////////
    //
    // RELATIONSHIPS
    //
    //
    //
    /**
     * patch relationship
     * @test
     * @group patch
     * @group rel
     */
    public function testPatchRelationships(){
        $model = $this->model->first();
        // PREPARE
        foreach($this->relationships() as $relationship){
            // get related model
            $relatedModel = "App\Api\V1\Models\\".ucfirst(substr($relationship,0,-1));
            // delete all relationships for testing
            $model->{$relationship}()->detach();
            $this->assertEquals(count($model->{$relationship}),0);
            // ADD VIA PATCH
            $response = $this->client->request('PATCH', '/'.$this->resource.'/'.$model->id.'/relationships/'.$relationship, [
                'headers' => ['Accept' => 'application/json'],
                'body' => json_encode([
                    "data" => [
                        'id' => (new $relatedModel)->all()->random(1)->id,
                        'type' => $relationship
                    ]
                ])
            ]);
            // ASSERTIONS
            $this->assertEquals(self::HTTP_NO_CONTENT, $response->getStatusCode());
            $this->assertEquals(1,$this->model->find($model->id)->{$relationship}->count());
            // OVERWRITE VIA PATCH
            $response = $this->client->request('PATCH', '/'.$this->resource.'/'.$model->id.'/relationships/'.$relationship, [
                'headers' => ['Accept' => 'application/json'],
                'body' => json_encode([
                    "data" => [
                        'id' => (new $relatedModel)->all()->random(1)->id,
                        'type' => $relationship
                    ]
                ])
            ]);
            // ASSERTIONS
            $this->assertEquals(self::HTTP_NO_CONTENT, $response->getStatusCode());
            $this->assertEquals(1,$this->model->find($model->id)->{$relationship}->count());
            // REMOVE VIA PATCH
            $response = $this->client->request('PATCH', '/'.$this->resource.'/'.$model->id.'/relationships/'.$relationship, [
                'headers' => ['Accept' => 'application/json'],
                'body' => json_encode([
                    "data" => [
                    ]
                ])
            ]);
            // ASSERTIONS
            $this->assertEquals(self::HTTP_NO_CONTENT, $response->getStatusCode());
            $this->assertEquals(0,$this->model->find($model->id)->{$relationship}->count());
        }
    }
    /**
     * patch relationship to wrong resource
     * @test
     * @group patch
     * @group rel
     */
    public function testPatchRelationshipsWithWrongResourceId(){
        foreach($this->relationships() as $relationship){
            // get related model
            $relatedModel = "App\Api\V1\Models\\".ucfirst(substr($relationship,0,-1));
            // POST
            $response = $this->client->request('PATCH', '/'.$this->resource.'/1/relationships/'.$relationship, [
                'headers' => ['Accept' => 'application/json'],
                'body' => json_encode([
                    "data" => [
                        'id' => (new $relatedModel)->all()->random(1)->id,
                        'type' => $relationship
                    ]
                ])
            ]);
            // ASSERTIONS
            $this->assertEquals(self::HTTP_NOT_FOUND, $response->getStatusCode());
        }
    }
    /**
     * patch relationship with wrong data
     * @test
     * @group patch
     * @group rel
     */
    public function testPatchRelationshipsWrongRelationshipData(){
        $model = $this->model->first();
        // PREPARE
        foreach($this->relationships() as $relationship){
            // delete all relationships for testing
            $model->{$relationship}()->detach();
            $this->assertEquals($model->find($model->id)->{$relationship}->count(),0);
            // get related model
            $relatedModel = "App\Api\V1\Models\\".ucfirst(substr($relationship,0,-1));
            // POST
            $response = $this->client->request('PATCH', '/'.$this->resource.'/'.$model->id.'/relationships/'.$relationship, [
                'headers' => ['Accept' => 'application/json'],
                'body' => json_encode([
                    "data" => [
                        'id' => (new $relatedModel)->all()->random(1)->id,
                        'type' => 'wrongType'
                    ]
                ])
            ]);
            // ASSERTIONS
            $this->assertEquals(self::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
            $this->assertEquals($model->find($model->id)->{$relationship}->count(),0);
            // POST
            $response = $this->client->request('PATCH', '/'.$this->resource.'/'.$model->id.'/relationships/'.$relationship, [
                'headers' => ['Accept' => 'application/json'],
                'body' => json_encode([
                    "data" => [
                        'id' => 'wrongId',
                        'type' => $relationship
                    ]
                ])
            ]);
            // ASSERTIONS
            $this->assertEquals(self::HTTP_BAD_REQUEST, $response->getStatusCode());
            $this->assertEquals($model->find($model->id)->{$relationship}->count(),0);
        }
    }
    /**
     * patch relationship to wrong relationship
     * @test
     * @group patch
     * @group rel
     */
    public function testPatchRelationshipsToWrongUrl(){
        $model = $this->model->first();
        foreach($this->relationships() as $relationship){
            // get related model
            $relatedModel = "App\Api\V1\Models\\".ucfirst(substr($relationship,0,-1));
            // POST
            $response = $this->client->request('PATCH', '/'.$this->resource.'/'.$model->id.'/relationships/wrongRelationship', [
                'headers' => ['Accept' => 'application/json'],
                'body' => json_encode([
                    "data" => [
                        'id' => (new $relatedModel)->all()->random(1)->id,
                        'type' => $relationship
                    ]
                ])
            ]);
            // ASSERTIONS
            $this->assertEquals(self::HTTP_NOT_FOUND, $response->getStatusCode());
        }
    }

// END OF FILE
}
