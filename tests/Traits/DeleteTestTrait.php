<?php

namespace Lukasoppermann\Testing\Traits;


trait DeleteTestTrait
{
    /*
     * @test delete the main resource by id
     */
    public function testDeleteResourceById(){
        // PREPARE
        $id = $this->model->first()->id;
        // CHECK BEFORE
        $this->assertNotNull($this->model->find($id));
        // DELETE
        $response = $this->client->request('DELETE', '/'.$this->resource.'/'.$id, [
            'headers' => [
                'Accept' => 'application/json',
            ]
        ]);
        // check status code & response body
        $this->assertEquals(self::HTTP_NO_CONTENT, $response->getStatusCode());
        $this->assertNull($this->model->find($id));
    }
    /*
     * @test delete SoftDeleted main resource by id
     * @group main
     * @group delete
     */
    public function testDeleteSoftDeletedResourceById(){
        // PREPARE
        $id = $this->model->first()->id;
        // CHECK BEFORE
        $model = $this->model->find($id);
        $this->assertNotNull($model);
        // Soft delete
        $model->delete();
        // DELETE
        $response = $this->client->request('DELETE', '/'.$this->resource.'/'.$id, [
            'headers' => [
                'Accept' => 'application/json',
            ]
        ]);
        // check status code & response body
        $this->assertEquals(self::HTTP_NO_CONTENT, $response->getStatusCode());
        $this->assertNull($this->model->find($id));
    }
    /**
     * @test delete the main resource by wrong id
     */
    public function testDeleteResourceByWrongId()
    {
        // CHECK BEFORE DELETE
        $response = $this->client->request('GET', '/'.$this->resource.'/1', [
            'headers' => ['Accept' => 'application/json']
        ]);
        $this->assertEquals(self::HTTP_NOT_FOUND, $response->getStatusCode());
        // DELETE
        $response = $this->client->request('DELETE', '/'.$this->resource.'/1', [
            'headers' => ['Accept' => 'application/json']
        ]);
        // ASSERTIONS
        $this->assertEquals(self::HTTP_NOT_FOUND, $response->getStatusCode());
    }
    /**
     * @test delete the relationships
     */
    public function testDeleteRelationships()
    {
        foreach($this->relationships() as $relationship){
            // PREPARE
            $model = $this->addRelatedItems($relationship);
            $relationshipId = $model->{$relationship}()->first()->id;
            // CHECK BEFORE DELETE
            $response = $this->client->request('GET', '/'.$this->resource.'/'.$model->id.'/relationships/'.$relationship, [
                'headers' => ['Accept' => 'application/json']
            ]);
            $this->assertEquals(self::HTTP_OK, $response->getStatusCode());
            // DELETE
            $response = $this->client->request('DELETE', '/'.$this->resource.'/'.$model->id.'/relationships/'.$relationship, [
               'headers' => ['Accept' => 'application/json'],
               'body' => json_encode(["data" => [[
                   "type" => $relationship,
                   "id" => $relationshipId
               ]]])
           ]);
            // ASSERTIONS
            $this->assertEquals(self::HTTP_NO_CONTENT, $response->getStatusCode());
            $this->assertEquals(count($model->{$relationship}()->find($relationshipId)),0);
        }
    }
    /**
     * delete the relationships with wrong relationship id
     */
    public function testDeleteRelationshipsWrongRelationshipData()
    {
        foreach($this->relationships() as $relationship){
            // PREPARE
            $model = $this->addRelatedItems($relationship);
            $relationshipId = $model->{$relationship}()->first()->id;
            // DELETE WITH WRONG ID
            $response = $this->client->request('DELETE', '/'.$this->resource.'/'.$model->id.'/relationships/'.$relationship, [
               'headers' => ['Accept' => 'application/json'],
               'body' => json_encode(["data" => [[
                   "type" => $relationship,
                   "id" => "1"
               ]]])
            ]);
            // ASSERTIONS
            $this->assertEquals(self::HTTP_NO_CONTENT, $response->getStatusCode());
            // DELETE WITH WRONG TYPE
            $response = $this->client->request('DELETE', '/'.$this->resource.'/'.$model->id.'/relationships/'.$relationship, [
               'headers' => ['Accept' => 'application/json'],
               'body' => json_encode(["data" => [[
                   "type" => "wrongType",
                   "id" => $relationshipId
               ]]])
            ]);
            // ASSERTIONS
            $this->assertEquals(self::HTTP_UNPROCESSABLE_ENTITY, $response->getStatusCode());
        }
    }
    /**
     * @test delete Relationships With Wrong Resource Id
     */
    public function testDeleteRelationshipsWithWrongResourceId()
    {
        foreach($this->relationships() as $relationship){
            // DELETE WITH WRONG ID
            $response = $this->client->request('DELETE', '/'.$this->resource.'/1/relationships/1/'.$relationship, [
               'headers' => ['Accept' => 'application/json'],
               'body' => json_encode(["data" => [[
                   "type" => $relationship,
                   "id" => "1"
               ]]])
            ]);
            // ASSERTIONS
            $this->assertEquals(self::HTTP_NOT_FOUND, $response->getStatusCode());
        }
    }

}
