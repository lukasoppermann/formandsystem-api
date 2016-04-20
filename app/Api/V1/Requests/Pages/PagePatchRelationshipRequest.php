<?php

namespace App\Api\V1\Requests\Pages;

use App\Api\V1\Requests\PatchRelationshipRequest;

class PagePatchRelationshipRequest extends PatchRelationshipRequest
{
    /**
     * The relationships the main resource can have
     *
     * @return array
     */
     protected function parentRelationships(){
         return[
             'pages',
             'collections',
             'fragments',
             'metadetails'
         ];
     }
    /**
     * check if request is authorized
     *
     * @method authorize
     *
     * @return array
     */
    protected function authorize(){
        return true;
    }

}
