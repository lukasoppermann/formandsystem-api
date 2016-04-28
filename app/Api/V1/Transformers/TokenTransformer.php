<?php

namespace App\Api\V1\Transformers;

use App\Api\V1\Transformers\ApiTransformer;
use League\Fractal\TransformerAbstract;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use App\Api\V1\Models\Token;

class TokenTransformer extends ApiTransformer
{

    public function transform(Token $token)
    {
        return [
            'id'            => $token->id,
            'type'          => 'Bearer',
            'expires_in'    => $token->expire_time,
        ];
    }
}
