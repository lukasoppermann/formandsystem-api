<?php

namespace App\Api\V1\Transformers;

use App\Api\V1\Transformers\ApiTransformer;
use League\Fractal\TransformerAbstract;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use App\Api\V1\Models\Collection;

class CollectionTransformer extends ApiTransformer
{
    protected $defaultIncludes = [
        // 'fragments'
        // 'pages'
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
            'relationships' => $this->relationshipsLinks('collections/'.$collection->id),
        ];
    }
    /*
     * include Pages
     */
    public function includePages( Collection $collection )
    {
        return $this->collection( $collection->pages, new PageTransformer, 'pages' );
    }
}
