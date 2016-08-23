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

    protected $request;
    protected $repository;
    protected $transformer;
    protected $resource = 'collections';

    public function __construct(CollectionRequest $request, Collection $repository, CollectionTransformer $transformer)
    {
        $this->request       = $request;
        $this->repository    = $repository;
        $this->transformer   = $transformer;
    }

    /*
     * index
     */
    public function index()
    {
        $result = $this->repository->all([
            'with_trashed' => $this->request->with_trashed,
            'only_trashed' => $this->request->only_trashed,
            'filter'       => $this->request->filter(),
        ]);

        return $this->response->paginator(
            $this->paginate($result, $this->request),
            $this->transformer,
            [
                'key' => $this->resource
            ]
        );
    }

    /*
     * show
     */
    public function show($id)
    {
        $result = $this->repository->getById($id, [
            'with_trashed' => $this->request->with_trashed,
            'only_trashed' => $this->request->only_trashed,
            'filter'       => $this->request->filter(),
        ]);
        // return resource items
        return $this->response->item(
            $result,
            $this->transformer,
            [
                'key' => $this->resource
            ]
        );
    }
}
