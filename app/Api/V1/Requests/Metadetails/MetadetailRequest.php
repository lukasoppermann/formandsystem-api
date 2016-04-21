<?php

namespace App\Api\V1\Requests\Metadetails;

use App\Api\V1\Requests\ResourceRequest;

abstract class MetadetailRequest extends ResourceRequest
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
