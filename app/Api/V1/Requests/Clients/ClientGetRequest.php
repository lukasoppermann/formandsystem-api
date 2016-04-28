<?php

namespace App\Api\V1\Requests\Clients;

use App\Api\V1\Requests\Clients\ClientRequest;

class ClientGetRequest extends ClientRequest
{
    /**
     * The scopes needed to do this request
     *
     * @return array
     */
    protected function scopes(){
        return [
            'client.gets'
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
}
