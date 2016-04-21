<?php

namespace App\Api\V1\Requests\Pages;

use App\Api\V1\Requests\ResourceRequest;

abstract class PageRequest extends ResourceRequest
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
