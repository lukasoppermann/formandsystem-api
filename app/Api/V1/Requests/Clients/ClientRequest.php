<?php

namespace App\Api\V1\Requests\Clients;

use App\Api\V1\Requests\ResourceRequest;

abstract class ClientRequest extends ResourceRequest
{
    /**
     * The relationships a resource can have
     *
     * @return array
     */
     protected function relationships(){
         return[
         ];
     }
}
