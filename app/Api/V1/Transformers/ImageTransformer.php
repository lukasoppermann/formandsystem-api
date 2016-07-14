<?php

namespace App\Api\V1\Transformers;

use App\Api\V1\Transformers\ApiTransformer;
use League\Fractal\TransformerAbstract;
use App\Api\V1\Models\Image;


class ImageTransformer extends ApiTransformer
{

    protected $defaultIncludes = [
        // 'fragments'
        // 'collections'
    ];
    /**
      * List of resources possible to include
      *
      * @var array
      */
    protected $availableIncludes = [
      'images',
      'ownedByImages',
      'ownedByCollections',
      'ownedByFragments',
    ];

    public function transform(Image $image)
    {
        // add upload link if not external
        $links['upload'] = $_ENV['API_DOMAIN'].'/uploads/'.$image->id;

        return [
            'id'            => $image->id,
            'link'          => $image->link,
            'filename'      => $image->filename,
            'slug'          => $image->slug,
            'bytesize'      => (int)$image->bytesize,
            'width'         => (int)$image->width,
            'height'        => (int)$image->height,
            'created_at'    => (string)$image->created_at,
            'updated_at'    => (string)$image->updated_at,
            'is_trashed'    => $this->isTrashed($image),
            'relationships' => $this->relationshipsLinks('images/'.$image->id),
            'links'         => $links,
        ];
    }
    /*
     * include images
     */
    public function includeImages( Image $image )
    {
        return $this->collection( $image->images, new ImageTransformer, 'images' );
    }
    /*
     * include images
     */
    public function includeOwnedByImages( Image $image )
    {
        return $this->collection( $image->ownedByImages, new ImageTransformer, 'images' );
    }
    /*
     * include Fragmens
     */
    public function includeOwnedByFragments( Image $image )
    {
        return $this->collection( $image->ownedByFragments, new FragmentTransformer, 'fragments' );
    }
    /*
     * include ownedByCollections
     */
    public function includeOwnedByCollections( Image $image )
    {
        return $this->collection( $image->ownedByCollections, new ColectionTransformer, 'collections' );
    }
    /*
     * include metadetails
     */
    public function includeMetadetails( Image $image )
    {
        return $this->collection( $image->metadetails, new MetadetailTransformer, 'metadetails' );
    }
    /*
     * include images
     */
    public function includeOwnedByMetadetails( Image $image )
    {
        return $this->collection( $image->ownedByMetadetails, new MetadetailTransformer, 'metadetails' );
    }

}
