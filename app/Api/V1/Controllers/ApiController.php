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
        // validate data
        $errors = (new $this->validator)->validatePost(array_merge(
            $receivedData,
            ['resourceType' => $request->json('data.type')]
        ));
        // return errors if vaildation fails
        if( $errors ){
            return $this->response->error($errors, 403);
        }
        // create item
        $model = $this->model->create($receivedData);
        // return result
        return $this->response->item($model, new $this->transformer, ['key' => $this->resource])->setStatusCode(201)->withHeader('Location', $_ENV['API_DOMAIN'].'/'.$this->resource.'/'.$model->id);
    }
    /*
    * update
    */
    public function update(Request $request, $id)
    {
        // init transformer
        $transformer = new $this->transformer;
        // get data from request
        $data = json_decode($request->getContent(), true)['data']['attributes'];
        // transform data
        $input = $transformer->transformPostData($data);
        // update item
        $model = $this->model->find($id);
        $model->fill($input);
        $model->save();
        // return result
        return $this->response->item($this->model->find($id), $transformer, ['key' => $this->resource])->setStatusCode(200);
    }
    /*
     * delete
     */
    public function delete($resource_id){
        $this->model->destroy($resource_id);

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
     * validate if the resource exists, if not, throws 404
     *
     * @method getRecivedData
     *
     * @param  Request $request
     *
     * @return array
     */
    protected function getRecivedData(Request $request){
        // get fiels from model
        $fields = $this->model->getFillable();
        // remove id
        array_splice($fields, array_search('id', $fields ), 1);
        // grab data for accepted fields
        foreach($request->json('data.attributes') as $key => $value){
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
}
