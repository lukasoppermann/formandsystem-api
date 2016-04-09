<?php

namespace App\Api\V1\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ApiController extends BaseController
{
    // trait
    use \Dingo\Api\Routing\Helpers;

    protected $availableFilters = [];

    protected $perPage = 20;

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
}
