<?php

namespace App\Api\V1\Requests\Clients;

use App\Api\V1\Requests\Clients\ClientRequest;

class ClientDeleteRequest extends ClientRequest
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
