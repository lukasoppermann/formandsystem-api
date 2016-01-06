<?php

namespace App\Api\V1\Transformers;

use League\Fractal\TransformerAbstract;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use App\Api\V1\Models\Collection;

class CollectionTransformer extends TransformerAbstract
{
    protected $defaultIncludes = [
        'pages'
    ];
    /**
      * List of resources possible to include
      *
      * @var array
      */
    protected $availableIncludes = [
      'pages'
    ];

    public function transform(Collection $collection)
    {
        return [
            'id'    => $collection->id,
            'name' => $collection->name,
            'slug' => $collection->slug,
        ];
    }

    public function includePages(Collection $collection){
        return $this->collection($collection->pages, new PageTransformer, 'pages' );
    }
}
