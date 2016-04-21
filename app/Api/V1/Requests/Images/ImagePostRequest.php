<?php

namespace App\Api\V1\Requests\Images;

use App\Api\V1\Requests\Images\ImageRequest;

class ImagePostRequest extends ImageRequest
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
            'type' => 'required|in:images',
            'attributes.link' => 'url|required',
            'attributes.slug' => 'string|required',
            'attributes.bytesize' => 'int|required',
            'attributes.width' => 'int|required',
            'attributes.height' => 'int|required',
        ];
    }
}
