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
        $page = $request->input('page', 1); // Get the current page or default to 1
        $offset = ($page * $this->perPage) - $this->perPage;

        return $this->response->paginator(
            new LengthAwarePaginator(
                $relatedModel->slice($offset, $this->perPage),
                $relatedModel->count(),
                $this->perPage,
                $page,
                [
                    'path' => $request->url(),
                    'query' => $request->query()
                ]
            ),
            new $transformer,
            ['key' => $type]
        );
    }

    /**
     * get paginated relationship data
     */
    public function getRelationship($id, $type, $relatedType, $data){

        return $this->response->array([
            'links' => [
                'self' => $_ENV['API_DOMAIN'].'/'.$type.'/'.$id.'/relationships/'.$relatedType,
                'related' => $_ENV['API_DOMAIN'].'/'.$type.'/'.$id.'/'.$relatedType
            ],
            'data' => $data[$relatedType]->map(function($item){
                return [
                    'id' => $item->id,
                    'type' => 'collections'
                ];
            })
        ]);

    }
}
