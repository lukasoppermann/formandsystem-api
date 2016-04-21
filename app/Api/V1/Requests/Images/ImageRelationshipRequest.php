<?php

namespace App\Api\V1\Requests\Images;

use App\Api\V1\Requests\RelationshipRequest;

abstract class ImageRelationshipRequest extends RelationshipRequest
{
    /**
     * The relationships a resource can have
     *
     * @return array
     */
    protected function relationships(){
         return[
             'images',
             'fragments',
         ];
    }

}
