<?php

namespace App\Api\V1\Requests\Tokens;

use App\Api\V1\Requests\Tokens\TokenRequest;

class TokenPostRequest extends TokenRequest
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
        return [

        ];
    }

    /**
     * check if request is authorize
     *
     * @method authorize
     *
     * @return bool
     */
    protected function authorize()
    {
        // no validation possible
        return true;
    }
}
