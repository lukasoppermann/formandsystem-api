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
      'fragments',
      'images'
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
            'relationships' => $this->relationshipsLinks('images/'.$image->id),
            'links'         => $links,
        ];
    }
    /*
     * include Fragmens
     */
    public function includeFragments( Image $image )
    {
        return $this->collection( $image->fragments, new FragmentTransformer, 'fragments' );
    }
    /*
     * include images
     */
    public function includeImages( Image $image )
    {
        return $this->collection( $image->images, new ImageTransformer, 'images' );
    }

}
