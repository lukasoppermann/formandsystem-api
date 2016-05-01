<?php

namespace App\Api\V1\Requests\Metadetails;

use App\Api\V1\Requests\ResourceRequest;

class MetadetailRequest extends ResourceRequest
{
    /**
     * The relationships a resource can have
     *
     * @return array
     */
    public function relationships(){
         return[
             'pages'
         ];
    }
    /**
     * Retuns needed scopes to perform a request
     *
     * @method scopes
     *
     * @return array
     */
    protected function scopes(){
        return [];
    }
    /**
     * Retuns rules
     *
     * @method rules
     *
     * @return array
     */
    protected function rules(){
        return [];
    }
}
