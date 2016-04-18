<?php

namespace App\Api\V1\Requests\Fragments;

use App\Api\V1\Requests\PostRequest;

class FragmentPostRequest extends PostRequest
{
    /**
     * The relationships a resource can have
     *
     * @return array
     */
     protected function relationships(){
         return[
             'pages',
             'fragments',
             'images'
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
            'type' => 'required|in:fragments',
            'attributes.name' => 'string',
            'attributes.type' => 'required|string',
            'attributes.data' => '',
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
