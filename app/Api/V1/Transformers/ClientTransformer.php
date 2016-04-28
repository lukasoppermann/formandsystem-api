<?php

namespace App\Api\V1\Transformers;

use App\Api\V1\Transformers\ApiTransformer;
use League\Fractal\TransformerAbstract;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use App\Api\V1\Models\Client;

class ClientTransformer extends ApiTransformer
{

    public function transform(Client $client)
    {
        return [
            'id'        => $client->id,
            'secret'    => $client->secret,
            'name'      => $client->name,
        ];
    }
}
