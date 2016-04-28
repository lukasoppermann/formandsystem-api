<?php

namespace App\Api\V1\Requests\Clients;

use App\Api\V1\Requests\Clients\ClientRequest;

class ClientPostRequest extends ClientRequest
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
            'type' => 'required|in:authentications',
            'attributes.name' => 'required|string',
        ];
    }
}
