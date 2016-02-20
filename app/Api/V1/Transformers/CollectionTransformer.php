<?php

namespace App\Api\V1\Transformers;

use League\Fractal\TransformerAbstract;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use App\Api\V1\Models\Collection;

class CollectionTransformer extends TransformerAbstract
{

    public function transform(Collection $collection)
    {
        return [
            'id'    => $collection->id,
            'name' => $collection->name,
            'slug' => $collection->slug,
            'links' => [[
                'rel' => 'pages',
                'uri' => $_ENV['API_DOMAIN'].'/collections/'.$collection->id.'/pages',
            ]]
        ];
    }
}
