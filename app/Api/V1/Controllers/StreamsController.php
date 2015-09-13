<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Models\Streams;
use App\Api\V1\Transformers\StreamTransformer;

class StreamsController extends ApiController
{
    public function show($stream)
    {
        $streams = Streams::all();

        return $this->response->collection($streams, new StreamTransformer);
    }
}
