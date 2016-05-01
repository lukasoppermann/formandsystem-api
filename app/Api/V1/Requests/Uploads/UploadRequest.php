<?php

namespace App\Api\V1\Requests\Uploads;

use App\Api\V1\Requests\ResourceRequest;

abstract class UploadRequest extends ResourceRequest
{
    /**
     * The relationships a resource can have
     *
     * @return array
     */
    public function relationships(){
         return[

         ];
    }

}
