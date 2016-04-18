<?php

namespace App\Api\V1\Requests\Collections;

use App\Api\V1\Requests\PostRequest;

class CollectionPostRequest extends PostRequest
{
    /**
     * The relationships a resource can have
     *
     * @return array
     */
     protected function relationships(){
         return[
             'pages'
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
            'type' => 'required|in:collections',
            'attributes.name' => 'required|string',
            'attributes.slug' => 'required|string|alpha_dash',
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
