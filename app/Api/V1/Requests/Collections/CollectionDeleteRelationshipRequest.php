<?php

namespace App\Api\V1\Requests\Collections;

use App\Api\V1\Requests\Collections\CollectionRelationshipRequest;

class CollectionDeleteRelationshipRequest extends CollectionRelationshipRequest
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
