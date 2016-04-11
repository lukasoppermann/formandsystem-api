<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Models\Collection;
use App\Api\V1\Transformers\CollectionTransformer;
use App\Api\V1\Transformers\PageTransformer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class CollectionsController extends ApiController
{
    /**
     * The filters that are allowed in requests
     *
     * @var array
     */
    protected $availableFilters = [
        'slug'
    ];
    /**
     * The filters that are allowed in requests
     *
     * @var array
     */
    protected $resource = 'collections';

    public function getPages(Request $request, $collection_id)
    {
        $collection = $this->validateResourceExists(Collection::find($collection_id));

        return $this->response->paginator(
            $collection->pages()->paginate($this->perPage),
            new PageTransformer ,
            ['key' => 'pages']
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
