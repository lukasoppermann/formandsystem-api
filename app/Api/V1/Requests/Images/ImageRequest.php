<?php

namespace App\Api\V1\Requests\Images;

use App\Api\V1\Requests\ResourceRequest;

abstract class ImageRequest extends ResourceRequest
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
