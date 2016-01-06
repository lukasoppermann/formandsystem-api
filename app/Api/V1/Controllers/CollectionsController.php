<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Models\Collection;
use App\Api\V1\Transformers\CollectionTransformer;
use App\Api\V1\Transformers\PageTransformer;

class CollectionsController extends ApiController
{
    public function index()
    {
        $collection = Collection::all();

        return $this->response->collection($collection, ['key' => 'collections']);
    }

    public function show($collection_id)
    {
        $collection = Collection::where('id', $collection_id)->get();

        return $this->response->collection($collection, new CollectionTransformer, ['key' => 'collections']);
    }

    public function getPages($collection_id)
    {
        $collection = Collection::where('id', $collection_id)->first()->pages;

        return $this->response->collection($collection, new PageTransformer, ['key' => 'pages']);
    }

}
