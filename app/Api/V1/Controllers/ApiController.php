<?php

namespace App\Api\V1\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ApiController extends BaseController
{
    // trait
    use \Dingo\Api\Routing\Helpers;
    /**
     * The main model that for the resource
     *
     * @var model
     */
    protected $model;
    /**
     * The main transformer that for the resource
     *
     * @var transformer
     */
    protected $transformer;
    /**
     * The validator for the resource
     *
     * @var validator
     */
    protected $validator;
    /**
     * The filters that are allowed in requests
     *
     * @var array
     */
    protected $availableFilters = [];
    /**
     * The number of items returned per page
     *
     * @var int
     */
    protected $perPage = 20;
    /**
     * @method __construct
     */
    public function __construct(){
        // get name from resources
        $name = ucfirst(substr($this->resource,0,-1));
        // init model
        $model = "App\Api\V1\Models\\".$name;
        $this->model = new $model;
        // init transfomer
        $this->transformer = "App\Api\V1\Transformers\\".$name."Transformer";
        // init validator
        $this->validator = "App\Api\V1\Validators\\".$name."Validator";
    }
    /*
     * index
     */
    public function index(Request $request)
    {
        // remove s from resource name
        $name = ucfirst(substr($this->resource,0,-1));
        // get filter resource
        $resource = $this->getFilteredResult($this->model, $request->input('filter'));
        // return result
        return $this->response->paginator($resource, new $this->transformer, ['key' => $this->resource]);
    }
    /*
     * show
     */
    public function show($id)
    {
        $item = $this->validateResourceExists($this->model->find($id));

        return $this->response->item($item, new $this->transformer, ['key' => $this->resource]);
    }
    /*
     * store
     */
    public function store(Request $request)
    {
        // get data from request
        $receivedData = $this->getRecivedData($request);
        $receivedRelationships = $this->getRecivedRelationships($request);
        // validate data
        $errors = (new $this->validator)->validatePost($request->json('data'));
        // return errors if vaildation fails
        if( $errors ){
            return $this->response->error($errors, 400);
        }
        // create item
        $model = $this->model->create($receivedData);
        // add relationships
        foreach($receivedRelationships as $key => $relationships){
            // check if relationship is valid
            if(!in_array($key, $this->relationships)){
                return $this->response()->errorBadRequest('Invalid relationship');
            }
            //
            $relatedModel = "App\Api\V1\Models\\".substr(ucfirst($key),0,-1);
            foreach($relationships['data'] as $relationship){
                $related = (new $relatedModel)->find($relationship['id']);
                if( $related && in_array($relationship['type'], $this->relationships)){
                    $model->{$key}()->save($related);
                }
                else {
                    return $this->response()->errorBadRequest('Invalid relationship');
                }
            }
        }
        // return result
        return $this->response->item($model, new $this->transformer, ['key' => $this->resource])->setStatusCode(201)->withHeader('Location', $_ENV['API_DOMAIN'].'/'.$this->resource.'/'.$model->id);
    }
    /*
    * update
    */
    public function update(Request $request, $id)
    {
        // get data from request
        $receivedData = $this->getRecivedData($request);
        $receivedRelationships = $this->getRecivedRelationships($request);
        // validate data
        $errors = (new $this->validator)->validatePatch($request->json('data'));
        // return errors if vaildation fails
        if( $errors ){
            return $this->response->error($errors, 400);
        }
        // update item
        $model = $this->model->find($id);
        if($model === null){
            return $this->response->errorNotFound();
        }

        $model->fill($receivedData);
        $model->save();

        // add relationships
        foreach($receivedRelationships as $key => $relationships){
            $relatedModel = "App\Api\V1\Models\\".substr(ucfirst($key),0,-1);
            $model->{$key}()->detach();
            foreach($relationships['data'] as $relationship){
                if(isset($relationship['id'])
                    && $related = (new $relatedModel)->find($relationship['id'])
                ){
                    $model->{$key}()->save($related);
                }
            }
        }
        // return result
        return $this->response->item($this->model->find($id), new $this->transformer, ['key' => $this->resource])->setStatusCode(200);
    }
    /*
     * delete
     */
    public function delete($resource_id){
        // if resource doesn't exist
        if($this->model->destroy($resource_id) === 0){
            return $this->response->errorNotFound();
        }

        return $this->response->noContent();
    }
    /*
     * delete
     */
    public function deleteRelationships(Request $request, $relationship, $id){
        // Validate resource
        $model = $this->validateResourceExists($this->model->find($id));
        // validate relationship
        if( !in_array($relationship, $this->relationships) ){
            return $this->response->errorNotFound();
        }
        // delete individual relationships
        $relationship_ids = [];
        // get ids
        foreach($request->json('data') as $relData){
            // check for correct types
            if($relationship !== $relData['type']){
                return $this->response->errorBadRequest();
            }
            $ids[] = $relData['id'];
        }
        // delete relationships
        $model->{$relationship}()->detach($ids);

        return $this->response->noContent();
    }

    /**
     * return filtered result
     * @usage: url.com/resource?filter
     */
    public function getFilteredResult($model, $filterList){

        $filters = $this->prepFilters($filterList);

        foreach($filters as $key => $value){

            if(!in_array($key, $this->availableFilters)){
                throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException('Filter "'.$key.'" is not available for for this resource.');
            }
            $model = $model->where($key, $value);

        }

        // no entry exists, throw exception, will be converted to jsonapi response
        if ($model->count() === 0) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
        }

        return $model->paginate($this->perPage);
    }

    /**
     * prepare filter items
     */
    public function prepFilters($filterList){
        if( trim($filterList) === "")
        {
            return [];
        }

        // check for correct format
        if( substr($filterList,0,1) !== "[" || substr($filterList,-1) !== "]" ){
            throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException('Malformed filter syntax.');
        }

        // buld filter array
        $filterList = explode(',',substr($filterList,1,-1));
        foreach($filterList as $filter){
            $filter = explode('=',$filter);
            $filters[$filter[0]] = $filter[1];
        }

        return $filters;
    }

    /**
     * get paginated related data
     */
    public function getRelated(Request $request, $relatedModel, $type){
        //
        $transformer = "App\Api\V1\Transformers\\".ucfirst(substr($type,0,-1))."Transformer";

        return $this->response->paginator(
            $relatedModel->paginate($this->perPage),
            new $transformer,
            ['key' => $type]
        );

    }

    /**
     * get relationship data
     */
     public function getRelationship($data){
         $relationships = [];
         // build relationship array
         foreach($data['ids'] as $id){
             $relationships[] = [
                 'id' => $id,
                 'type' => $data['type']
             ];
         }

         return $this->response->array([
             'data' => $relationships,
             'links' => [
                 'self' => $_ENV['API_DOMAIN'].'/'.$data['parent_type'].'/'.$data['parent_id'].'/relationships/'.$data['type'],
                 'related' => $_ENV['API_DOMAIN'].'/'.$data['parent_type'].'/'.$data['parent_id'].'/'.$data['type']
             ],
         ]);
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
     * update relationship
     *
     * @method updateRelationships
     *
     * @param  Request $request
     * @param  Int $id
     *
     * @return array
     */
    public function updateRelationships(Request $request, $id){
        // get type from url
        $type = $request->segment(4);
        // get ids
        $relationshipIds = $this->getRelationshipsIds($request->json('data'), $type);
        // get model
        $model = $this->model->find($id);
        // detach old relationships
        $model->{$type}()->detach();
        // attach new relationships
        try{
            $model->{$type}()->attach($relationshipIds);
        }catch(\Exception $e){
            return $this->response->error('', 403);
        }
        return $this->response->noContent();
    }
    /**
     * add new relationships
     *
     * @method storeRelationships
     *
     * @param  Request $request
     * @param  Int $id
     *
     * @return array
     */
    public function storeRelationships(Request $request, $id){
        // get type from url
        $type = $request->segment(4);
        // check data
        if(!$data = $request->json('data')){
            return $this->response->error('Missing request body', 403);
        }
        // get ids
        $relationshipIds = $this->getRelationshipsIds($data, $type);
        // get model
        $model = $this->model->find($id);
        // attach new relationships
        try{
            $model->{$type}()->attach($relationshipIds);
        }catch(\Exception $e){
            return $this->response->error('', 403);
        }
        return $this->response->noContent();
    }
    /**
     * gets attributes from received data for fillable fields
     *
     * @method getRecivedData
     *
     * @param  Request $request
     *
     * @return array
     */
    protected function getRecivedData(Request $request){
        $attributes = $request->json('data.attributes');
        if(!is_array($attributes)){
            $attributes = [];
        }
        // get fiels from model
        $fields = $this->model->getFillable();
        // remove id
        array_splice($fields, array_search('id', $fields ), 1);
        // grab data for accepted fields
        $output = [];
        foreach($attributes as $key => $value){
            if( in_array($key,$fields) ){
                if(is_array($value)){
                    $value = json_encode($value);
                }
                $output[$key] = $value;
            }
        }
        // return data
        return $output;
    }
    /**
     * gets relationships from received data, for
     *
     * @method getRecivedRelationships
     *
     * @param  Request $request
     *
     * @return array
     */
    protected function getRecivedRelationships(Request $request){
        $relationships = $request->json('data.relationships');
        // check for existance
        if(!is_array($relationships)){
            $relationships = [];
        }
        // grab data for accepted fields
        $output = [];
        foreach($relationships as $key => $value){
            if( in_array($key,$this->relationships) ){
                // check if single resource & make sure to turn into array
                if(isset($value['data']['id'])){
                    $value['data'] = [$value['data']];
                }
                // return relations
                foreach($value as $v){
                    $output[$key] = $value;
                }
            }
        }
        // return data
        return $output;
    }
    /**
     * turns relationship data into array
     *
     * @method prepareRelationshipsData
     *
     * @param  $data
     *
     * @return array
     */
    protected function getRelationshipsIds($relationships, $type){
        // if single resource return id in array
        if(isset($relationships['id']) && isset($relationships['type']) && $relationships['type'] === $type){
            return [$relationships['id']];
        }
        // grab ids and return
        $ids = [];
        foreach($relationships as $rel){
            if(isset($rel['type']) && $rel['type'] === $type && isset($rel['id'])){
                $ids[] = $rel['id'];
            }
        }
        // return
        return $ids;
    }
}
