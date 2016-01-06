<?php

namespace App\Api\V1\Transformers;

use League\Fractal\TransformerAbstract;
use App\Api\V1\Models\Fragment;

class FragmentTransformer extends TransformerAbstract
{

    protected $defaultIncludes = [
        'fragments'
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
        ];
    }

    public function includeFragments( Fragment $fragment )
    {
        return $this->collection( $fragment->fragments, new FragmentTransformer, 'fragments' );
    }
}
