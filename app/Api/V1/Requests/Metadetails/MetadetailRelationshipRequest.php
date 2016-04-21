<?php

namespace App\Api\V1\Requests\Metadetails;

use App\Api\V1\Requests\RelationshipRequest;

abstract class MetadetailRelationshipRequest extends RelationshipRequest
{
    /**
     * The relationships a resource can have
     *
     * @return array
     */
    protected function relationships(){
         return[
             'pages',
         ];
    }

}
