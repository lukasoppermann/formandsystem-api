<?php

namespace App\Api\V1\Requests\Images;

use App\Api\V1\Requests\Images\ImageRelationshipRequest;

class ImagePatchRelationshipRequest extends ImageRelationshipRequest
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
