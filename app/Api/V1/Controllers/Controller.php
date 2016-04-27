<?php

namespace App\Api\V1\Controllers;

use Laravel\Lumen\Routing\Controller as LumenController;
use Illuminate\Http\Request;
use \Dingo\Api\Routing\Helpers;

abstract class Controller extends LumenController
{
    // trait
    use Helpers;
    /**
     * The general namespace for the api
     *
     * @var api_namespace
     */
    protected $api_namespace = "App\Api\V1\\";
    /**
     * The current request
     *
     * @var request
     */
    protected $request;
    /**
     * @method __construct
     */
    public function __construct(Request $request){
        $this->request = $this->newRequest($request);
    }
    /**
     * returns current request
     *
     * @method newRequest
     *
     * @return request
     */
    public function newRequest(Request $request){
        // request namespace
        $request_name = $this->api_namespace."Requests\\".$this->resourceName().'s\\'.
            // build request file name
            $this->resourceName().
            // add method
            ucfirst(strtolower($request->getMethod())).
            // add relationship, if it is
            $this->isRelationshipRequest($request).
            // add request
            'Request';
        // return a new mode
        return new $request_name($request);
    }
    /**
     * return 'Relationship' or void
     *
     * @method isRelationshipRequest
     *
     * @param  Request               $request
     *
     * @return [string|void]
     */
    protected function isRelationshipRequest(Request $request){
        if($request->segment(3) === 'relationships'){
            return 'Relationship';
        }
    }
    /**
     * returns current resources model
     *
     * @method newModel
     *
     * @return model
     */
    public function newModel($model = NULL){
        // get model namespace
        if($model === NULL){
            $model = $this->resourceName();
        }
        $model = $this->api_namespace."Models\\".$model;
        // return a new mode
        return new $model;
    }
    /**
     * returns current resources transformer
     *
     * @method newTransformer
     *
     * @return transformer
     */
    public function newTransformer($transformer = NULL){
        // get transformer namespace
        if($transformer === NULL){
            $transformer = $this->resourceName();
        }
        // get transformer namespace
        $transformer = $this->api_namespace."Transformers\\".$transformer."Transformer";
        // return a new transformer
        return new $transformer;
    }
    /**
     * returns current resources validator
     *
     * @method newValidator
     *
     * @return validator
     */
    public function newValidator(){
        // get validator namespace
        $validator = $this->api_namespace."Validators\\".$this->resourceName()."Validator";
        // return a new validator
        return new $validator;
    }
    /**
     * returns current resources name in singular and ucfist
     *
     * @method resourceName
     *
     * @return return string
     */
    public function resourceName(){
        return ucfirst(substr($this->resource,0,-1));
    }
    /**
     * validate if the resource exists, if not, throws 404
     *
     * @method validateResourceExists
     *
     * @param  resource object $resource
     *
     * @return resource object $resource
     */
    public function validateResourceExists($resource, $msg = 'Resource not found.'){
        // throw 404 exception if resource does not exists
        if ($resource === null) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException($msg);
        }
        // return resource if it does exist
        return $resource;
    }
    /**
     * throws error if relationship does not exist
     *
     * @method validateRelationship
     *
     * @return bool
     */
    protected function validateRelationship($related = null){
        if(!$this->isRelated($related)){
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('The resource "'.$this->resource.'" has no relationship to "'.$related.'".');
        }
    }
    /**
     * returns true if provided relationships exists for current model
     *
     * @method isRelated
     *
     * @return bool
     */
    protected function isRelated($related = null){
        return in_array($related, $this->relationships);
    }
    /**
     * turns relationship data into array
     *
     * @method getRelationshipsIds
     *
     * @param  $data
     *
     * @return array
     */
    protected function getRelationshipsIds($relationships, $type){
        // wrap in case of single item
        if(isset($relationships['id'])){
            $relationships = [$relationships];
        }
        // grab ids and return
        foreach((array) $relationships as $relationship){
            // return error if wrong type or item does not exist
            if($relationship['type'] !== $type ){
                throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException('Invalid relationship with type "'.$relationship['type'].'" and id "'.$relationship['id'].'".');
            }
            // grab id
            $ids[] = $relationship['id'];
        }
        // return
        return isset($ids) ? $ids : [];
    }
    /**
     * check if relationships exist
     *
     * @method validateRelationshipsIds
     *
     * @param  $data
     *
     * @return array
     */
    protected function validateRelationshipsIds($ids=[], $type){
        // relatedModel
        $relatedModel = $this->api_namespace.'Models\\'.ucfirst(substr($type,0,-1));
        $relatedModel = new $relatedModel;
        // check ids
        foreach($ids as $id){
            // return error if wrong type or item does not exist
            if($relatedModel->find($id) === NULL ){
                throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException('Invalid relationship with type "'.$type.'" and id "'.$id.'".');
            }
        }
    }
}
