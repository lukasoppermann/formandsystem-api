<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Requests\CollectionRequest;
use App\Api\V1\Repositories\Collection;
use App\Api\V1\Transformers\CollectionTransformer;
use App\Api\V1\Traits\PaginationTrait;

/**
 * Collection resource representation.
 *
 * @Resource("Collections", uri="/collections")
 */
class NewCollectionsController extends Controller
{
    use PaginationTrait;
    /*
     * index
     */
    public function newIndex(CollectionRequest $request, Collection $collection, CollectionTransformer $transformer)
    {
        $result = $collection->all([
            'with_trashed' => $this->request->with_trashed,
            'only_trashed' => $this->request->only_trashed,
            'filter'       => $this->request->filter(),
        ]);
        // return new LengthAwarePaginator($items, $total, $perPage, $currentPage = null, array $options = []);
        // // get model instance
        // $model = $this->newModel();
        // // with trashed items
        // if($this->request->with_trashed === true){
        //     $model = $model->withTrashed();
        // }
        // // only trashed items
        // if($this->request->only_trashed === true){
        //     $model = $model->onlyTrashed();
        // }
        // // apply filters
        // foreach((array) $this->request->filter() as $key => $values){
        //     $model = $model->whereIn($key, $values);
        // }
        // // return result
        // $parameters = $request->all();
        // unset($parameters['page']);
        // // needs return rawurldecode($this->paginator->url($page));
        // // in League\Fractal\Pagination\IlluminatePaginatorAdapter on line 102
        return $this->response->paginator(
            $this->paginate($result, $request),
            $transformer,
            [
                'key' => 'collections'
            ]
        );
    }
}
