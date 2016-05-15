<?php

namespace Lukasoppermann\Testing\Traits;


trait DeleteTestTrait
{
    /*
     * @test delete the main resource by id
     * @group main
     * @group delete
     */
    public function testDeleteResourceById(){
        // PREPARE
        $id = $this->model->first()->id;
        // CHECK BEFORE
        $this->assertNotNull($this->model->find($id));
        // DELETE
        $response = $this->client()->request('DELETE', '/'.$this->resource.'/'.$id, [
            'headers' => $this->headers(),
        ]);
        // check status code & response body
        $this->assertEquals(self::HTTP_NO_CONTENT, $response->getStatusCode());
        $this->assertNull($this->model->find($id));
    }
    /*
     * delete SoftDeleted main resource by id
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
        $response = $this->client()->request('DELETE', '/'.$this->resource.'/'.$id, [
            'headers' => $this->headers(),
        ]);
        // check status code & response body
        $this->assertEquals(self::HTTP_NO_CONTENT, $response->getStatusCode());
        $this->assertNull($this->model->find($id));
    }
    /**
     * @test delete the main resource by wrong id
     * @group main
     * @group delete
     */
    public function testDeleteResourceByWrongId()
    {
        // CHECK BEFORE DELETE
        $response = $this->client()->request('GET', '/'.$this->resource.'/1', [
            'headers' => $this->headers(),
        ]);
        $this->assertEquals(self::HTTP_NOT_FOUND, $response->getStatusCode());
        // DELETE
        $response = $this->client()->request('DELETE', '/'.$this->resource.'/1', [
            'headers' => $this->headers(),
        ]);
        // ASSERTIONS
        $this->assertEquals(self::HTTP_NOT_FOUND, $response->getStatusCode());
    }
    /**
     * @test delete the relationships
     * @group rel
     * @group delete
     */
    public function testDeleteRelationships()
    {
        foreach($this->relationships() as $relationship){
            // get model
            $model = $this->model->withTrashed()->first();
            // add relationships
            $this->addRelatedItems($model, $relationship);
            $relationshipId = $model->{$relationship}()->first()->id;
            // CHECK BEFORE DELETE
            $response = $this->client()->request('GET', '/'.$this->resource.'/'.$model->id.'/relationships/'.$relationship, [
                'headers' => $this->headers(),
            ]);
            $this->assertEquals(self::HTTP_OK, $response->getStatusCode());
            // DELETE
            $response = $this->client()->request('DELETE', '/'.$this->resource.'/'.$model->id.'/relationships/'.$relationship, [
               'headers' => $this->headers(),
               'body' => json_encode(["data" => [[
                   "type" => strtolower(str_replace('ownedBy','',$relationship)),
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
     * @group rel
     * @group delete
     */
    public function testDeleteRelationshipsWrongRelationshipData()
    {
        // get model
        $model = $this->model->withTrashed()->first();
        foreach($this->relationships() as $relationship){
            // add relationships
            $this->addRelatedItems($model, $relationship);
            $relationshipId = $model->{$relationship}()->first()->id;
            // DELETE WITH WRONG ID
            $response = $this->client()->request('DELETE', '/'.$this->resource.'/'.$model->id.'/relationships/'.$relationship, [
               'headers' => $this->headers(),
               'body' => json_encode(["data" => [[
                   "type" => strtolower(str_replace('ownedBy','',$relationship)),
                   "id" => "1"
               ]]])
            ]);
            // ASSERTIONS
            $this->assertEquals(self::HTTP_NO_CONTENT, $response->getStatusCode());
            // DELETE WITH WRONG TYPE
            $response = $this->client()->request('DELETE', '/'.$this->resource.'/'.$model->id.'/relationships/'.$relationship, [
               'headers' => $this->headers(),
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
     * @group rel
     * @group delete
     */
    public function testDeleteRelationshipsWithWrongResourceId()
    {
        foreach($this->relationships() as $relationship){
            // DELETE WITH WRONG ID
            $response = $this->client()->request('DELETE', '/'.$this->resource.'/1/relationships/1/'.$relationship, [
               'headers' => $this->headers(),
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
