<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Requests\ApiRequest;

use Illuminate\Http\Request;

use Illuminate\Pagination\LengthAwarePaginator;

class RelationshipController extends Controller
{
    /*
     * delete relationship
     */
    public function deleteRelationships(Request $request, $relationship, $id){
        // Validate resource

        $model = $this->validateResourceExists(
            $this->newModel()->findWithTrashed($id),
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
    public function getRelated(Request $request, $id, $relationship){
        // validate resource
        $model = $this->validateResourceExists($this->newModel()->findWithTrashed($id));
        // validate realtionship
        $this->validateRelationship($relationship);
        // prepare transformer
        $type = strtolower(str_replace('ownedBy','',$relationship));
        $transformer = $this->api_namespace."Transformers\\".ucfirst(substr($type,0,-1))."Transformer";
        // with trashed items
        if($this->request->with_trashed === true){
            $model->setRelationshipFilter(['with_trashed' => true]);
        }
        // only trashed items
        if($this->request->only_trashed === true){
            $model->setRelationshipFilter(['only_trashed' => true]);
        }
        // return paginated result
        return $this->response->paginator(
            $model->{$relationship}()->paginate($this->perPage),
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
            $this->newModel()->findWithTrashed($resource_id),
            'The resource of type "'.$this->resource.'" with the id of "'.$resource_id.'" does not exist.'
        );
        // validate relationship
        $this->validateRelationship($type);
        // build relationship array
        $relationships = [];
        // with trashed items
        if($this->request->with_trashed === true){
            $model->setRelationshipFilter(['with_trashed' => true]);
        }
        // only trashed items
        if($this->request->only_trashed === true){
            $model->setRelationshipFilter(['only_trashed' => true]);
        }
        //
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
            $this->newModel()->findWithTrashed($resource_id),
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
    public function updateRelationships(Request $request, $resource_id, $relatedType){
        // validate main resource
        $model = $this->validateResourceExists(
           $this->newModel()->findWithTrashed($resource_id),
           'The resource of type "'.$this->resource.'" with the id of "'.$resource_id.'" does not exist.'
       );
       // validate relationship
       $this->validateRelationship($relatedType);
        // get ids
        $relationshipIds = $this->getRelationshipsIds($request->json('data'), $relatedType);
        // validate ids
        $this->validateRelationshipsIds($relationshipIds, $relatedType);
        // detach old relationships
        $model->{$relatedType}()->detach();
        // attach new relationships
        $model->{$relatedType}()->attach($relationshipIds);
        // return no content
        return $this->response->noContent();
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
}
