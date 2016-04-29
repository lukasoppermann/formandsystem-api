<?php

namespace Lukasoppermann\Testing\Traits;


trait GetTestTrait
{
    /**
     * @test getting the main resource listing
     *
     * @method getResource
     *
     * @link GET /resource
     *
     */
    public function getResource(){
        // CALL
        $response = $this->getClientResponse('/'.$this->resource);
        // GET DATA
        $received = $this->getResponseArray($response)['data'][0];
        // ASSERTIONS
        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());
        $this->assertValid($received, $this->resource()->blueprint());
    }
    /**
     * @test getting the main resource by id
     *
     * @method getResourceById
     *
     */
    public function getResourceById(){
        // CALL
        $response = $this->getClientResponse('/'.$this->resource.'/'.$this->model->first()->id);
        // GET DATA
        $received = $this->getResponseArray($response)['data'];
        // ASSERTIONS
        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($this->model->first()->id, $received['id']);
        $this->assertValid($received, $this->resource()->blueprint());
    }
    /**
    * @test getting the main resource by wrong id
    *
    * @method getResourceByWrongId
    *
    */
    public function getResourceByWrongId(){
        $response = $this->getClientResponse($this->resource.'/1');
        // ASSERTIONS
        $this->checkErrorResponse($response, 'HTTP_NOT_FOUND');
    }
    /**
     * @test getting the main resource by filter
     *
     * @method getResourceByFilter
     *
     */
    public function getResourceByFilter()
    {
        $filter = $this->resource()->filter->first();
        // CALL
        $response = $this->getClientResponse('/'.$this->resource.'?filter['.$filter.']='.$this->model->first()->{$filter});
        // GET DATA
        $received = $this->getResponseArray($response)['data'][0];
        // TEST PAGINATION
        $this->isPaginated($response);
        // ASSERTIONS
        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($this->model->first()->{$filter}, $received['attributes'][$filter]);
        $this->assertValid($received, $this->resource()->blueprint());
    }
    /**
     * @test getting the main resource by wrong filter
     *
     * @method getResourceByWrongFilter
     *
     */
    public function getResourceByWrongFilter()
    {
        // CALL
        $response = $this->getClientResponse('/'.$this->resource.'?filter[wrongFilter]=someValue]');
        // TEST PAGINATION
        $this->checkErrorResponse($response, 'HTTP_UNPROCESSABLE_ENTITY');
    }
    /**
     * @test getting the main resource by wrong filter parameter
     *
     * @method getResourceByWrongFilter
     *
     */
    public function getResourceByWrongFilterParameter()
    {
        // PREPARE
        $filter = $this->resource()->filter->first();
        // CALL
        $response = $this->getClientResponse('/'.$this->resource.'?filter['.$filter.']=wrongParameter');
        // TEST PAGINATION
        $this->isPaginated($response);
        // ASSERTIONS
        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());
    }

    /**
     * test getting the related resource e.g. /pages
     */
    public function getRelated()
    {
        foreach($this->relationships() as $relationship){
            $model = $this->addRelatedItems($relationship);
            // CALL
            $response = $this->getClientResponse('/'.$this->resource.'/'.$model->id.'/'.$relationship);
            // TEST PAGINATION
            $this->isPaginated($response);
            // check specific structure & data
            $received = $this->getResponseArray($response)['data'][0];
            // ASSERTIONS
            $this->assertEquals(self::HTTP_OK, $response->getStatusCode());
            $this->assertValid($received, $this->resources[$relationship]->blueprint());
        }
    }
    /**
     * test getting the related resource e.g. /pages using a wrong resource id
     */
    public function getRelatedNoRelatedItems(){
        foreach($this->relationships()as $relationship){
            $this->model->first()->{$relationship}()->detach();
            // CALL
            $response = $this->getClientResponse('/'.$this->resource.'/'.$this->model->first()->id.'/'.$relationship);
            // TEST PAGINATION
            $this->isPaginated($response);
            // ASSERTIONS
            $this->assertEquals(self::HTTP_OK, $response->getStatusCode());
        }
    }
    /**
     * test getting the related resource e.g. /pages using a wrong resource id
     */
    public function getRelatedWithWrongResourceId(){
        foreach($this->relationships()as $relationship){
            $response = $this->getClientResponse($this->resource.'/1/'.$relationship);
            // ASSERTIONS
            $this->checkErrorResponse($response, 'HTTP_NOT_FOUND');
        }
    }
    /**
     * test getting the relationships e.g. /relationships/pages
     */
    public function getRelationships()
    {
        foreach($this->relationships()as $relationship){
            $model = $this->addRelatedItems($relationship);
            // CALL
            $response = $this->getClientResponse('/'.$this->resource.'/'.$model->id.'/relationships/'.$relationship);
            // check specific structure & data
            $received = $this->getResponseArray($response)['data'][0];
            // ASSERTIONS
            $this->assertEquals(self::HTTP_OK, $response->getStatusCode());
            $this->assertValid($received, [
                'id' => 'string',
                'type' => 'in:'.$relationship,
            ]);
        }
    }
    /**
     * test getting the related resource e.g. /pages using a wrong resource id
     */
    public function getRelationshipsNoRelatedItems(){
        foreach($this->relationships()as $relationship){
            $this->model->first()->{$relationship}()->detach();
            // CALL
            $response = $this->getClientResponse('/'.$this->resource.'/'.$this->model->first()->id.'/relationships/'.$relationship);
            // ASSERTIONS
            $this->assertEquals(self::HTTP_OK, $response->getStatusCode());
        }
    }
    /**
     * test getting the related resource e.g. /pages using a wrong resource id
     */
    public function getRelationshipsWithWrongResourceId(){
        foreach($this->relationships()as $relationship){
            $response = $this->getClientResponse($this->resource.'/1/relationships/'.$relationship);
            // ASSERTIONS
            $this->checkErrorResponse($response, 'HTTP_NOT_FOUND');
        }
    }
    //------------------------------------------
    //
    // UTILITY FUNCTIONS
    //
    /*
     * add related items
     */
    public function addRelatedItems($relationship){
        // get model with relationships
        $model = $this->model->first();
        // remove relationships
        $model->{$relationship}()->detach();
        // Prepare related model
        $relatedModel = "App\Api\V1\Models\\".ucfirst(substr($relationship,0,-1));
        $ids = (new $relatedModel)->all()->random(2)->lists('id')->toArray();
        // attach models
        $model->{$relationship}()->attach($ids);
        // return model
        return $model;
    }
}
