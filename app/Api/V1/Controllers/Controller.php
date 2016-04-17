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
        // get model namespace
        $requestName = $this->api_namespace."Requests\\".$this->resourceName().'s\\'.$this->resourceName().ucfirst(strtolower($request->getMethod())).'Request';
        // return a new mode
        return new $requestName($request);
    }
    /**
     * returns current resources model
     *
     * @method newModel
     *
     * @return model
     */
    public function newModel(){
        // get model namespace
        $model = $this->api_namespace."Models\\".$this->resourceName();
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
    public function newTransformer(){
        // get transformer namespace
        $transformer = $this->api_namespace."Transformers\\".$this->resourceName()."Transformer";
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
    public function validateResourceExists($resource){
        // throw 404 exception if resource does not exists
        if ($resource === null) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Relationship does not exists');
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
}
