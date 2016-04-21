<?php

namespace App\Api\V1\Requests\Collections;

use App\Api\V1\Requests\ResourceRequest;

abstract class CollectionRequest extends ResourceRequest
{
    /**
     * The relationships a resource can have
     *
     * @return array
     */
     protected function relationships(){
         return[
            'pages'
         ];
     }
}
