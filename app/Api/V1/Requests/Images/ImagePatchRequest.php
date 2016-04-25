<?php

namespace App\Api\V1\Requests\Images;

use App\Api\V1\Requests\Images\ImageRequest;

class ImagePatchRequest extends ImageRequest
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
    /**
     * validation rules
     *
     * @method rules
     *
     * @return array
     */
    protected function rules(){
        return [
            'id' => 'required|string',
            'type' => 'required|in:images',
            'attributes.slug' => 'string',
        ];
    }
}
