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
    //   'fragments',
    //   'collections'
    ];

    public function transform(Image $image)
    {
        return [
            'id'            => $image->id,
            'link'          => $image->link,
            'slug'          => $image->slug,
            'bytesize'      => (int)$image->bytesize,
            'width'         => (int)$image->width,
            'height'        => (int)$image->height,
            'created_at'    => (string)$image->created_at,
            'updated_at'    => (string)$image->updated_at,
        ];
    }

}
