<?php

namespace App\Api\V1\Transformers;

use App\Api\V1\Transformers\ApiTransformer;
use League\Fractal\TransformerAbstract;
use App\Api\V1\Models\Fragment;

class FragmentTransformer extends ApiTransformer
{

    protected $defaultIncludes = [
        'fragments',
        'images',
        'metadetails',
        'collections',
    ];
    /**
      * List of resources possible to include
      *
      * @var array
      */
    protected $availableIncludes = [
      'fragments',
      'ownedByFragments',
      'collections',
      'ownedByCollections',
      'ownedByPages',
      'images',
      'metadetails',
    ];

    public function transform(Fragment $fragment)
    {
        return [
            'id'    => $fragment->id,
            'name' => $fragment->name,
            'type' => $fragment->type,
            'data' => $fragment->data,
            'created_at' => (string)$fragment->created_at,
            'updated_at' => (string)$fragment->updated_at,
            'is_trashed'    => $this->isTrashed($fragment),
            'relationships' => $this->relationshipsLinks('fragments/'.$fragment->id),
        ];
    }

    public function includeFragments( Fragment $fragment )
    {
        return $this->collection( $fragment->fragments, new FragmentTransformer, 'fragments' );
    }

    public function includeOwnedByFragments( Fragment $fragment )
    {
        return $this->collection( $fragment->ownedByFragments, new FragmentTransformer, 'fragments' );
    }

    public function includeImages( Fragment $fragment )
    {
        return $this->collection( $fragment->images, new ImageTransformer, 'images' );
    }

    public function includeOwnedByPages( Fragment $fragment )
    {
        return $this->collection( $fragment->ownedByPages, new PageTransformer, 'pages' );
    }

    public function includeOwnedByCollections( Fragment $fragment )
    {
        return $this->collection( $fragment->ownedBycollections, new CollectionTransformer, 'collections' );
    }

    public function includeCollections( Fragment $fragment )
    {
        return $this->collection( $fragment->collections, new CollectionTransformer, 'collections' );
    }

    public function includeMetadetails( Fragment $fragment )
    {
        return $this->collection( $fragment->metadetails, new MetadetailTransformer, 'metadetails' );
    }
}
