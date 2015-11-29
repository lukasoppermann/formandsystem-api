<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Models\Collection;
use App\Api\V1\Transformers\CollectionTransformer;

class CollectionsController extends ApiController
{
    public function show($collection)
    {
        $collection = Collection::where('type', $collection)->take(20)->get();

        return $this->response->collection($collection, new CollectionTransformer);
    }
}
