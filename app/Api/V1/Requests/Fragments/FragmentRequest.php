<?php

namespace App\Api\V1\Requests\Fragments;

use App\Api\V1\Requests\ResourceRequest;

abstract class FragmentRequest extends ResourceRequest
{
    /**
     * The relationships a resource can have
     *
     * @return array
     */
    protected function relationships(){
        return[
            'pages',
            'fragments',
            'images'
        ];
    }

}
