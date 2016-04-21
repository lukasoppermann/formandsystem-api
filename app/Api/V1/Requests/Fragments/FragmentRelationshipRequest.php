<?php

namespace App\Api\V1\Requests\Fragments;

use App\Api\V1\Requests\RelationshipRequest;

abstract class FragmentRelationshipRequest extends RelationshipRequest
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
