<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use App\Api\V1\Requests\ApiRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class ApiController extends Controller
{
    /**
     * The main namespace
     *
     * @var string
     */
    protected $api_namespace = "App\Api\V1\\";
    /**
     * The number of items returned per page
     *
     * @var int
     */
    protected $perPage = 20;
    /*
     * index
     */
    public function index()
    {
        // get model instance
        $model = $this->newModel();
        // apply filters
        foreach((array) $this->request->input('filter') as $key => $value){
            $model = $model->where($key, $value);
        }
        // return result
        return $this->response->paginator($model->paginate($this->perPage), $this->newTransformer(), ['key' => $this->resource]);
    }
    /*
     * show
     */
    public function show($id)
    {
        // validate that the requested resource exists
        $item = $this->validateResourceExists(
            $this->newModel()->find($id),
            'The resource of type "'.$this->resource.'" with the id of "'.$id.'" does not exist.'
        );
        // return resource items
        return $this->response->item($item, $this->newTransformer(), ['key' => $this->resource]);
    }
    /*
     * store
     */
    public function store(Request $request)
    {
        // get data from request
        $receivedData = $this->getRecivedData($request);
        // get relationship data from request
        $relationships = $this->getRecivedRelationships($request);
        // create item
        $model = $this->newModel()->create($receivedData['data']);
        // add relationships
        $this->saveRelationships($model, $relationships);
        // return result
        return $this->response
            ->item($model, $this->newTransformer(), ['key' => $this->resource])
            ->setStatusCode(201)
            ->withHeader('Location', $_ENV['API_DOMAIN'].'/'.$this->resource.'/'.$model->id);
    }
    /*
    * update
    */
    public function update(Request $request, $id)
    {
        // get data from request
        $receivedData = $this->getRecivedData($request);
        // get relationship data from request
        $relationships = $this->getRecivedRelationships($request);
        // validate item
        $model = $this->validateResourceExists(
            $this->newModel()->find($id),
            'The resource of type "'.$this->resource.'" with the id "'.$id.'" does not exist.'
        );
        // Add new data to model
        $model->fill($receivedData['data']);
        // Save Model
        $model->save();
        // add relationships
        $this->removeRelationships($model, $relationships);
        $this->saveRelationships($model, $relationships);
        // return result
        return $this->response->item($this->newModel()->find($id), $this->newTransformer(), ['key' => $this->resource])->setStatusCode(200);
    }
    /*
     * delete
     */
    public function delete($resource_id){
        // validate resource
        $model = $this->validateResourceExists(
            $this->newModel()->find($resource_id),
            'The resource of type "'.$this->resource.'" with the id "'.$resource_id.'" does not exist.'
        );
        $model->destroy($resource_id);

        return $this->response->noContent();
    }
    ///////////////////////////////////////////////////////////////////////////
    //
    // RELATIONSHIPS
    //
    /*
     * delete relationship
     */
    public function deleteRelationships(Request $request, $relationship, $id){
        // Validate resource
        $model = $this->validateResourceExists(
            $this->newModel()->find($id),
            'The resource of type "'.$this->resource.'" with the id of "'.$id.'" does not exist.'
        );
        // Validate relationship
        $this->validateRelationship($relationship);
        // get individual relationships ids
        $relationship_ids = $this->getRelationshipsIds($request->json('data'), $relationship);
        // delete relationships
        $model->{$relationship}()->detach($relationship_ids);
        // respond no content
        return $this->response->noContent();
    }
    /**
     * get the related items data for the main resource
     *
     * @method getRelated
     *
     * @param  Request    $request
     * @param  [string]     $resource_id
     * @param  [string]     $type
     *
     * @return [Response]
     */
    public function getRelated(Request $request, $resource_id, $type){
        // validate resource
        $model = $this->validateResourceExists(
            $this->newModel()->find($resource_id),
            'The resource of type "'.$this->resource.'" with the id of "'.$resource_id.'" does not exist.'
        );
        // validate realtionship
        $this->validateRelationship($type);
        // prepare transformer
        $transformer = $this->api_namespace."Transformers\\".ucfirst(substr($type,0,-1))."Transformer";
        // return paginated result
        return $this->response->paginator(
            $model->{$type}()->paginate($this->perPage),
            new $transformer,
            ['key' => $type]
        );

    }

    /**
     * get the relationships array (id & type) for the given resource
     *
     * @method getRelationships
     *
     * @param  Request          $request
     * @param  [string]           $resource_id
     * @param  [string]           $type
     *
     * @return [Response]
     */
     public function getRelationships(Request $request, $resource_id, $type){
         // validate
         $model = $this->validateResourceExists(
            $this->newModel()->find($resource_id),
            'The resource of type "'.$this->resource.'" with the id of "'.$resource_id.'" does not exist.'
        );
        // validate relationship
        $this->validateRelationship($type);
        // build relationship array
        $relationships = [];
        foreach($model->{$type}->lists('id')->toArray() as $id){
            $relationships[] = [
                'id' => $id,
                'type' => $type
            ];
        }
        // return response
        return $this->response->array([
            'data' => $relationships,
            'links' => [
                'self' => $_ENV['API_DOMAIN'].'/'.$this->resource.'/'.$resource_id.'/relationships/'.$type,
                'related' => $_ENV['API_DOMAIN'].'/'.$this->resource.'/'.$resource_id.'/'.$type
            ],
        ]);
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
     public function storeRelationships(Request $request, $resource_id, $relatedType){
         // validate main resource
         $model = $this->validateResourceExists(
            $this->newModel()->find($resource_id),
            'The resource of type "'.$this->resource.'" with the id of "'.$resource_id.'" does not exist.'
        );
        // validate relationship
        $this->validateRelationship($relatedType);
        // get ids
        $relationshipIds = $this->getRelationshipsIds($request->json('data'), $relatedType);
        // validate ids
        $this->validateRelationshipsIds($relationshipIds, $relatedType);
        // attach new relationships
        $model->{$relatedType}()->attach($relationshipIds);
        // return HTTP_NO_CONTENT
        return $this->response->noContent();
     }
    /**
     * update relationships
     *
     * @method updateRelationships
     *
     * @param  [Request] $request
     * @param  [int] $id
     *
     * @return [Response]
     */
    public function updateRelationships(Request $request, $id, $type){
        // get type from url
        dd($type);
        // get ids
        $relationshipIds = $this->getRelationshipsIds($request->json('data'), $type);
        // get model
        $model = $this->newModel()->find($id);
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
        // get fields from model
        $fields = $this->newModel()->getFillable();

        // remove id
        array_splice($fields, array_search('id', $fields ), 1);
        // grab data for accepted fields
        $output = [];
        $ignored = false;
        foreach($attributes as $key => $value){
            if( in_array($key,$fields) ){
                if(is_array($value)){
                    $value = json_encode($value);
                }
                $output[$key] = $value;
            }
            else {
                $ignored = true;
            }
        }
        // return data
        return [
            'data' => $output,
            'ignored' => $ignored
        ];
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
        // grab data for accepted fields
        foreach((array) $request->json('data.relationships') as $key => $value){
            // wrap relationship in array if single relationship
            if(isset($value['data']['id'])){
                $value['data'] = [$value['data']];
            }
            // return relations
            foreach($value['data'] as $relationship){
                // get related model namespace
                $relatedModelName = $this->api_namespace."Models\\".substr(ucfirst($key),0,-1);
                // get related Model
                $relatedModel = (new $relatedModelName)->find($relationship['id']);
                $relationships[$key][] = $relatedModel;
                // valudate resource
                $this->validateResourceExists(
                    $relatedModel,
                    'The resource of type "'.$relationship['type'].'" with the id of "'.$relationship['id'].'" does not exist.'
                );
            }
        }
        // return data
        return isset($relationships) ? $relationships : [];
    }

    /**
     * save an array of relationship to a model
     *
     * @method saveRelationships
     *
     * @param  [model]            $model
     * @param  [array]            $relationships
     */
    protected function saveRelationships($model, $relationships = []){
        foreach($relationships as $type => $items){
            foreach($items as $related){
                $model->{$type}()->save($related);
            }
        }
    }
    /**
     * remove relationships from a model
     *
     * @method removeRelationships
     *
     * @param  [model]            $model
     * @param  [array]            $relationships
     */
    protected function removeRelationships($model, $relationships = []){
        // loop through all provided relationship changes
        foreach($relationships as $type => $items){
            // reset ids
            $ids = [];
            // get ids for current relationship of $type
            foreach($items as $related){
                $ids[] = $related['id'];
            }
            // remive items
            $model->{$type}()->detach($ids);
        }
    }
}
