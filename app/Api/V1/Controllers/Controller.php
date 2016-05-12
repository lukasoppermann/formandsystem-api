<?php

namespace App\Api\V1\Controllers;

use Laravel\Lumen\Routing\Controller as LumenController;
use Illuminate\Http\Request;
use \Dingo\Api\Routing\Helpers;
use \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Api\V1\Traits\TranslateTrait;

abstract class Controller extends LumenController
{
    // trait
    use Helpers;
    use TranslateTrait;
    /**
     * The current resource
     *
     * @var $resource
     */
    protected $resource;
    /**
     * The number of items returned per page
     *
     * @var int
     */
    protected $perPage = 20;
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
        // find resource
        $this->resource = $this->getResourceFromUrl($request);
        // build new request
        $this->request = $this->newRequest($request);
    }
    /**
     * get requested resource and compare with allowed endpoints
     *
     * @method getResourceFromUrl
     *
     * @return [type]
     */
    protected function getResourceFromUrl(Request $request){
        return $request->segment(1);
    }
    /**
     * returns current request
     *
     * @method newRequest
     *
     * @return request
     */
    public function newRequest(Request $request){
        // build relationship request name
        $request_name = $this->api_namespace."Requests\RelationshipRequest";
        // overwrite if not relationship request
        if($request->segment(3) !== 'relationships'){
            // Normal request
            $request_name = $this->api_namespace."Requests\\".
            // build request file name
            $this->resourceName().
            // add request
            'Request';
        }
        // return a new mode
        return new $request_name($request);
    }
    /**
     * returns model instance for current resources or resource provided as param
     *
     * @param string|NULL $resource [name of the resource for which the model should be created]
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function newModel($resource = NULL){
        // use controller model if none provided
        if($resource === NULL){
            $resource = $this->resourceName();
        }
        // get model namespace
        $model = $this->api_namespace."Models\\".ucfirst(rtrim(str_replace('ownedBy','',$resource),'s'));
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
        // use controller transformer if none provided
        if($transformer === NULL){
            $transformer = $this->resourceName();
        }
        // get transformer namespace
        $transformer = $this->api_namespace."Transformers\\".$transformer."Transformer";
        // return a new transformer
        return new $transformer;
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
            $error = $this->trans('errors.resource_missing');
            throw new NotFoundHttpException($error);
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
        if( !in_array($related, $this->request->relationships()) ){
            $error = $this->trans('errors.not_related', ['resource' => $this->resource,'related' => $related]);
            throw new NotFoundHttpException($error);
        }
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
            if($relationship['type'] !== strtolower(str_replace('ownedBy','',$type)) ){
                $error = $this->trans('errors.invalid_relationship', [
                    'id' => $relationship['id'],
                    'type' => $relationship['type']
                ]);
                throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException($error);
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
        $relatedModel = $this->newModel($type);
        // check ids
        foreach($ids as $id){
            // return error if wrong type or item does not exist
            if($relatedModel->find($id) === NULL ){
                $error = $this->trans('errors.invalid_relationship', [
                    'id' => $id,
                    'type' => $type
                ]);
                throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException($error);
            }
        }
    }
}
