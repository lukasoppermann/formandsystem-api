<?php

namespace App\Api\V1\Requests\Pages;

use App\Api\V1\Requests\RelationshipRequest;

abstract class PageRelationshipRequest extends RelationshipRequest
{
    /**
     * The relationships a resource can have
     *
     * @return array
     */
    protected function relationships(){
         return[
             'pages',
             'collections',
             'fragments',
             'metadetails'
         ];
    }

}
