<?php

namespace App\Api\V1\Transformers;

use App\Api\V1\Transformers\ApiTransformer;
use League\Fractal\TransformerAbstract;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use App\Api\V1\Models\Client;

class ClientTransformer extends ApiTransformer
{

    protected $defaultIncludes = [
        'details'
    ];
    /**
      * List of resources possible to include
      *
      * @var array
      */
    protected $availableIncludes = [
        'details'
    ];

    public function transform(Client $client)
    {
        return [
            'id'        => $client->id,
            'secret'    => $client->secret,
            'name'      => $client->name,
        ];
    }

    /*
     * include Pages
     */
    public function includeDetails( Client $client )
    {
        return $this->collection( $client->details, new DetailTransformer, 'details' );
    }
}
