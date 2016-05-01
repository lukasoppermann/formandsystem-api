<?php

namespace App\Api\V1\Transformers;

use App\Api\V1\Transformers\ApiTransformer;
use League\Fractal\TransformerAbstract;
use App\Api\V1\Models\Metadetail;


class MetadetailTransformer extends ApiTransformer
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
      'pages'
    ];

    public function transform(Metadetail $metadetails)
    {
        return [
            'id'            => $metadetails->id,
            'type'          => $metadetails->type,
            'value'          => $this->decode($metadetails->value),
            'created_at'    => (string)$metadetails->created_at,
            'updated_at'    => (string)$metadetails->updated_at,
            'is_trashed'    => $this->isTrashed($metadetails),
            'relationships' => $this->relationshipsLinks('metadetails/'.$metadetails->id),
        ];
    }
    /*
     * include Pages
     */
    public function includePages( Metadetail $metadetail )
    {
        return $this->collection( $metadetail->pages, new PageTransformer, 'pages' );
    }

}
