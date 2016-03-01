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
        $collection = $this->validateResourceExists(Collection::find($collection_id));

        return $this->response->item($collection, new CollectionTransformer, ['key' => 'collections']);
    }

    public function getPages(Request $request, $collection_id)
    {
        $collection = $this->validateResourceExists(Collection::find($collection_id));

        return $this->getRelated(
            $request,
            $collection->pages,
            'pages'
        );
    }

    public function getPagesRelationships(Request $request, $collection_id){
        $collection = $this->validateResourceExists(Collection::find($collection_id));
        // return relationship
        return $this->getRelationship([
            'ids' => $collection->pages->lists('id'),
            'type' => 'pages',
            'parent_id' => $collection_id,
            'parent_type' => 'collections'
        ]);

    }

}
