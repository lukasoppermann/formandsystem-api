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
            'attributes.link' => 'url|required_if:bytesize,width,height',
            'attributes.slug' => 'string',
            'attributes.bytesize' => 'int|required_if:link,width,height',
            'attributes.width' => 'int|required_if:link,bytesize,height',
            'attributes.height' => 'int|required_if:link,bytesize,width',
        ];
    }
}
