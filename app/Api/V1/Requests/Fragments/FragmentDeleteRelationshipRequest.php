<?php

namespace App\Api\V1\Requests\Fragments;

use App\Api\V1\Requests\Fragments\FragmentRelationshipRequest;

class FragmentDeleteRelationshipRequest extends FragmentRelationshipRequest
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
}
