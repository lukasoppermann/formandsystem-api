<?php

namespace App\Api\V1\Transformers;

use League\Fractal\TransformerAbstract;
use App\Api\V1\Models\Streams;

class StreamTransformer extends TransformerAbstract{

    public function transform(Streams $stream) {
        return [
            'id' => $stream->id,
            'type' => $stream->type,
            'attributes' => [
                'page_id' => $stream->page_id,
                'position' => (int) $stream->position,
            ]
        ];
    }
}
