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
            'attributes.slug'       => 'string|required',
            'attributes.link'       => 'string|required_without:data.attributes.filename',
            'attributes.filename'   => 'string|required_without:data.attributes.link',
            'attributes.bytesize'   => 'int|required_with:data.attributes.filename',
            'attributes.width'      => 'int|required_with:data.attributes.filename',
            'attributes.height'     => 'int|required_with:data.attributes.filename',
        ];
    }
}
