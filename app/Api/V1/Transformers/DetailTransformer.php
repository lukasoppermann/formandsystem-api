<?php

namespace App\Api\V1\Transformers;

use App\Api\V1\Transformers\ApiTransformer;
use League\Fractal\TransformerAbstract;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use App\Api\V1\Models\Detail;

class DetailTransformer extends ApiTransformer
{

    protected $defaultIncludes = [
        // 'details'
    ];
    /**
      * List of resources possible to include
      *
      * @var array
      */
    protected $availableIncludes = [
        // 'details'
    ];

    public function transform(Detail $detail)
    {
        return [
            'id'      => $detail->id,
            'type'    => $detail->type,
            'data'    => $this->decode($detail->data),
        ];
    }
}
