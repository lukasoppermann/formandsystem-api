<?php

namespace App\Api\V1\Requests\Images;

use App\Api\V1\Requests\PatchRequest;

class ImagePatchRequest extends PatchRequest
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
    /**
     * check if request is authorized
     *
     * @method authorize
     *
     * @return array
     */
    protected function authorize(){
        return true;
    }

}
