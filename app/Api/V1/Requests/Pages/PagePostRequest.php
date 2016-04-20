<?php

namespace App\Api\V1\Requests\Pages;

use App\Api\V1\Requests\PostRequest;

class PagePostRequest extends PostRequest
{
    /**
     * The relationships a resource can have
     *
     * @return array
     */
     protected function relationships(){
         return[
             'pages',
             'collections',
             'fragments',
             'metadetails'
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
            'type' => 'required|in:pages',
            'attributes.menu_label' => 'required|string',
            'attributes.slug' => 'required|string|alpha_dash',
            'attributes.published' => 'required|boolean',
            'attributes.language' => 'required|string|size:2',
            'attributes.title' => 'string',
            'attributes.description' => 'string'
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
