<?php

namespace App\Api\V1\Transformers;

use App\Api\V1\Transformers\ApiTransformer;
use League\Fractal\TransformerAbstract;
use App\Api\V1\Models\Fragment;

class FragmentTransformer extends ApiTransformer
{

    protected $defaultIncludes = [
        'fragments'
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

    public function transform(Fragment $fragment)
    {
        return [
            'id'    => $fragment->id,
            'name' => $fragment->name,
            'type' => $fragment->type,
            'data' => $fragment->data,
            'fragments' => $this->collection( $fragment->fragments, new FragmentTransformer ),
            'created_at' => (string)$fragment->created_at,
            'updated_at' => (string)$fragment->updated_at,
            'relationships' => $this->relationshipsLinks('fragments/'.$fragment->id),
        ];
    }

    public function includeFragments( Fragment $fragment )
    {
        return $this->collection( $fragment->fragments, new FragmentTransformer, 'fragments' );
    }

    public function includeImages( Fragment $fragment )
    {
        return $this->collection( $fragment->images, new ImageTransformer, 'images' );
    }
}
