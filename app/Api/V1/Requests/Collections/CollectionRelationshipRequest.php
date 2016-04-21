<?php

namespace App\Api\V1\Requests\Collections;

use App\Api\V1\Requests\RelationshipRequest;

abstract class CollectionRelationshipRequest extends RelationshipRequest
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
