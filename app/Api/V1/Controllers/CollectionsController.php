<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Models\Collection;
use App\Api\V1\Transformers\CollectionTransformer;
use App\Api\V1\Transformers\PageTransformer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class CollectionsController extends ApiController
{
    public function index()
    {
        $collection = Collection::paginate();

        return $this->response->paginator($collection, new CollectionTransformer, ['key' => 'collections']);
    }

    public function show($collection_id)
    {
        $collection = Collection::find($collection_id);

        return $this->response->item($collection, new CollectionTransformer, ['key' => 'collections']);
    }

    public function getPages(Request $request, $collection_id)
    {
        // get the pages for the specific collection
        $collection = Collection::where('id', $collection_id)->first()->pages;

        //
        $page = $request->input('page', 1); // Get the current page or default to 1
        $perPage = 2;
        $offset = ($page * $perPage) - $perPage;

        return $this->response->paginator(new LengthAwarePaginator($collection->slice($offset, $perPage), $collection->count(), $perPage, $page, ['path' => $request->url(), 'query' => $request->query()]), new PageTransformer, ['key' => 'pages']);
    }

}
