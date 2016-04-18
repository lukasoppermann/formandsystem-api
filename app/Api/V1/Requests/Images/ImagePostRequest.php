<?php

namespace App\Api\V1\Requests\Images;

use App\Api\V1\Requests\PostRequest;

class ImagePostRequest extends PostRequest
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
            'type' => 'required|in:images',
            'attributes.link' => 'url|required',
            'attributes.slug' => 'string|required',
            'attributes.bytesize' => 'int|required',
            'attributes.width' => 'int|required',
            'attributes.height' => 'int|required',
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
