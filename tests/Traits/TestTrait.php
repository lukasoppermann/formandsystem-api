<?php

namespace Lukasoppermann\Testing\Traits;

trait TestTrait
{
    /**
     * return current resource
     *
     * @method resource
     *
     * @return resource object
     */
    public function resource(){
        return $this->resources[$this->resource];
    }
    /**
     * return current resources relationships
     *
     * @method relationships
     *
     * @return resource object
     */
    public function relationships(){
        return $this->resources[$this->resource]->relationships();
    }
    /**
     * Decode json response to array
     *
     * @return array
     */
    public function getResponseArray($response)
    {
        return json_decode((string) $response->getBody(), true);
    }
    /**
     * get the response from server
     */
    public function getClientResponse($url, $headers = [])
    {
        return $this->client->get($url, [
            'headers' => array_merge([
                'Accept' => 'application/json',
            ], $headers),
        ]);
    }

    /**
     * test error response
     */
    public function checkErrorResponse($response, $errorType){
        $received = $this->getResponseArray($response);
        // check status code
        $this->assertEquals(constant("self::$errorType"), $response->getStatusCode());
        // check for correct structure
        $expected = [
            'error' => [
                'message' => 'string',
                'status_code' => 'integer|in:'.constant("self::$errorType")
            ]
        ];

        $this->assertValid($received, $expected);
    }
    /**
     * test for pagination
     */
    public function isPaginated($response){
        // get response
        $reponse = $this->getResponseArray($response);
        // test for meta
        if(!isset($reponse['meta'])){
            $this->fail('No Pagination or meta object missing.');
        }
        // compare pagination
        $expected = [
            'pagination' => [
                'total' => 'required|integer',
                'count' => 'required|integer',
                "per_page" => 'required|integer',
                "current_page" => 'required|integer|in:1',
                "total_pages" => 'required|integer',
                "links" => 'array'
            ]
        ];
        // ASSERTION
        $this->assertValid($reponse['meta'], $expected);
    }
    //------------------------------------------
    //
    // UTILITY FUNCTIONS
    //
    /*
     * add related items
     */
    public function addRelatedItems($model, $relationship){
        // remove relationships
        $model->{$relationship}()->detach();
        // get related model
        $relatedModel = $this->newModel($relationship);
        // get related items
        $ids = $relatedModel->all()->random(2)->lists('id')->toArray();
        // remove relationships to not have circular relationships
        if(method_exists($relatedModel->find($ids[0]), $relationship)){
            if(method_exists($relatedModel->find($ids[0])->{$relationship}(), 'detach')){
                $relatedModel->find($ids[0])->{$relationship}()->detach();
                $relatedModel->find($ids[1])->{$relationship}()->detach();
            }
        }
        // attach models
        $model->{$relationship}()->attach($ids);
    }
    /**
     * get a new model
     *
     * @method newModel
     *
     * @param  [string]          $resourceName
     *
     * @return [Model]
     */
    protected function newModel($resourceName){
        $relatedModel = "App\Api\V1\Models\\".ucfirst(substr(str_replace('ownedBy','',$resourceName),0,-1));
        return (new $relatedModel);
    }
}
