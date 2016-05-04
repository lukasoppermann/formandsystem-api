<?php

namespace App\Api\V1\Requests\Tokens;

use App\Api\V1\Requests\ResourceRequest;

abstract class TokenRequest extends ResourceRequest
{
    /**
     * The relationships a resource can have
     *
     * @return array
     */
     public function relationships(){
         return[
         ];
     }
}
