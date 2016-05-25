<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Requests\ApiRequest;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

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
        // with trashed items
        if($this->request->with_trashed === true){
            $model = $model->withTrashed();
        }
        // only trashed items
        if($this->request->only_trashed === true){
            $model = $model->onlyTrashed();
        }
        // apply filters
        foreach((array) $this->request->filter() as $key => $values){
            $model = $model->whereIn($key, $values);
        }
        // return result
        //TODO: Fix so that filters are in next link
        // $page_and_query = trim(preg_replace('/page=\d+/','',preg_replace('/\/([^?])*\?/','',$this->request->getRequestUri())),'&').'&page';
        // return $this->response->paginator($model->paginate($this->perPage, ['*'],$page_and_query), $this->newTransformer(), ['key' => $this->resource]);
        return $this->response->paginator($model->paginate($this->perPage), $this->newTransformer(), ['key' => $this->resource]);
    }
    /*
     * show
     */
    public function show($id)
    {
        // validate that the requested resource exists
        $item = $this->validateResourceExists(
            $this->newModel()->findWithTrashed($id),
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
        $model = $this->newModel()->create($receivedData);
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
        // get Model
        $model = $this->newModel()->findWithTrashed($id);
        // validate item
        $this->validateResourceExists($model,
            'The resource of type "'.$this->resource.'" with the id "'.$id.'" does not exist.'
        );
        // get data from request
        $receivedData = $this->getRecivedData($request, $model->acceptedFields());
        // get relationship data from request
        $relationships = $this->getRecivedRelationships($request);
        // restore or softDelete item
        if(array_key_exists('is_trashed',$receivedData)){
            $model->setTrashed($receivedData['is_trashed']);
            unset($receivedData['is_trashed']);
        }
        // Add new data to model
        $model->fill($receivedData);
        // Save Model
        $model->save();
        // add relationships
        $this->removeRelationships($model, $relationships);
        $this->saveRelationships($model, $relationships);
        // return result
        return $this->response->item($this->newModel()->withTrashed()->where('id', $id)->first(), $this->newTransformer(), ['key' => $this->resource])->setStatusCode(200);
    }
    /*
     * delete
     */
    public function delete($resource_id){
        // validate resource
        $model = $this->validateResourceExists(
            $this->newModel()->findWithTrashed($resource_id),
            'The resource of type "'.$this->resource.'" with the id "'.$resource_id.'" does not exist.'
        );
        // force delete to really delete soft deletes
        $model->forceDelete();

        return $this->response->noContent();
    }
    ////////////////////////////////////////////
    /// Utility
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
            return [];
        }
        // grab data for accepted fields
        $output = [];
        foreach($attributes as $key => $value){
            if(is_array($value)){
                $value = json_encode($value);
            }
            $output[$key] = $value;
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
        // grab data for accepted fields
        foreach((array) $request->json('data.relationships') as $key => $value){
            // wrap relationship in array if single relationship
            if(isset($value['data']['id'])){
                $value['data'] = [$value['data']];
            }
            // return relations
            foreach($value['data'] as $relationship){
                // get related model namespace
                $relatedModel = $this->newModel($key);
                // get related Model
                $relationships[$key][] = $relatedModel->find($relationship['id']);
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
            foreach($items as $item) {
                $model->{$type}()->save($item);
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
