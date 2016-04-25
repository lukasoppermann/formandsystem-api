<?php

namespace Lukasoppermann\Testing\Traits;


trait PatchTestTrait
{
    /*
     * patch the main resource by id
     */
    public function patchResourceById(){
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
     * patch the main resource by wrong id
     */
    public function patchResourceWrongId(){
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
     */
    public function patchResourceWrongType(){
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
     */
    public function patchResourceByIdWithoutAttributes(){
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
     */
    public function patchResourceByIdNoBody(){
        // PATCH
        $response = $this->client->request('PATCH', '/'.$this->resource.'/'.$this->model->first()->id, [
            'headers' => ['Accept' => 'application/json']
        ]);
        // ASSERTIONS
        $this->assertEquals(self::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
    }
    /*
     * patch the main resource by id with wrong body
     */
    public function patchResourceIncompleteData(){
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
     */
    public function patchResourceWithMultipleRelationships(){
        // PREPARE
        $model = $this->model->first();
        $relationshipData = [];
        foreach($this->relationships() as $relationship){
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
     */
    public function patchResourceWithOneRelationship(){
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
     */
    public function patchResourceWithWrongRelationships(){
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
     */
    public function patchResourceWithWrongRelationshipTypes(){
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
     */
    public function patchRelationships(){
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
     */
    public function patchRelationshipsWithWrongResourceId(){
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
     */
    public function patchRelationshipsWrongRelationshipData(){
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
     */
    public function patchRelationshipsToWrongUrl(){
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
