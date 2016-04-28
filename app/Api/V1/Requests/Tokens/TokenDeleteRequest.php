<?php

namespace App\Api\V1\Requests\Tokens;

use App\Api\V1\Requests\Authentications\AuthenticationRequest;

class TokenDeleteRequest extends AuthenticationRequest
{
    /**
     * The scopes needed to do this request
     *
     * @return array
     */
    protected function scopes(){
        return [

        ];
    }
    /**
     * validation rules
     *
     * @method rules
     *
     * @return array
     */
    protected function rules(){

    }
}
