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
      'pages',
      'ownedByPages',
      'collections',
      'ownedByCollections',
    ];

    public function transform(Collection $collection)
    {
        return [
            'id'            => $collection->id,
            'name'          => $collection->name,
            'slug'          => $collection->slug,
            'is_trashed'    => $this->isTrashed($collection),
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
    /*
     * include owning collections
     */
    public function includeOwnedByPages( Collection $collection )
    {
        return $this->collection( $collection->ownedByPages, new PageTransformer, 'pages' );
    }
    /*
     * include Collections
     */
    public function includeCollections( Collection $collection )
    {
        return $this->collection( $collection->collections, new CollectionTransformer, 'collections' );
    }
    /*
     * include owning collections
     */
    public function includeOwnedByCollections( Collection $collection )
    {
        return $this->collection( $collection->ownedByCollections, new CollectionTransformer, 'collections' );
    }

}
