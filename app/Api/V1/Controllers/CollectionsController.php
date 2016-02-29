<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Models\Collection;
use App\Api\V1\Transformers\CollectionTransformer;
use App\Api\V1\Transformers\PageTransformer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class CollectionsController extends ApiController
{
    protected $availableFilters = [
        'slug'
    ];

    public function index(Request $request)
    {
        $collection = $this->getFilteredResult(new Collection, $request->input('filter'));

        return $this->response->paginator($collection, new CollectionTransformer, ['key' => 'collections']);
    }

    public function show($collection_id)
    {
        $collection = Collection::find($collection_id);

        // no entry exists, throw exception, will be converted to jsonapi response
        if ($collection === null) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
        }

        return $this->response->item($collection, new CollectionTransformer, ['key' => 'collections']);
    }

    public function getPages(Request $request, $collection_id)
    {
        // get the pages for the specific collection
        $collection = Collection::find($collection_id)->pages;
        //
        $page = $request->input('page', 1); // Get the current page or default to 1
        $offset = ($page * $this->perPage) - $this->perPage;

        $paginator = new LengthAwarePaginator($collection->slice($offset, $this->perPage), $collection->count(), $this->perPage, $page, ['path' => $request->url(), 'query' => $request->query()]);

        return $this->response->paginator($paginator, new PageTransformer, ['key' => 'pages']);
    }

    public function getRelationshipsPages(Request $request, $collection_id){

        $ids = Collection::find($collection_id)->pages->lists('id');

        return $this->getRelationship([
            'ids' => $ids,
            'type' => 'pages',
            'parent_id' => $collection_id,
            'parent_type' => 'collections'
        ]);

    }

}
