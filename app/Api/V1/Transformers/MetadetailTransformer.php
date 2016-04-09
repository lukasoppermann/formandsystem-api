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
    //   'fragments',
    //   'collections'
    ];

    public function transform(Metadetail $metadetails)
    {
        return [
            'id'            => $metadetails->id,
            'type'          => $metadetails->type,
            'value'          => $this->decode($metadetails->value),
            'created_at'    => (string)$metadetails->created_at,
            'updated_at'    => (string)$metadetails->updated_at,
        ];
    }

    public function receivedDataJson($data){
        if( count($data) > 0){
            return [
                'type'          => $data['type'],
                'value'         => $this->encode($data['value']),
            ];
        }
        return false;
    }

}
