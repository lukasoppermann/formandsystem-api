<?php

namespace App\Api\V1\Requests\Pages;

use App\Api\V1\Requests\PatchRequest;

class PagePatchRequest extends PatchRequest
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
            'id' => 'required|string',
            'attributes.menu_label' => 'string',
            'attributes.slug' => 'string|alpha_dash',
            'attributes.published' => 'boolean',
            'attributes.language' => 'string|size:2',
            'attributes.title' => 'string',
            'attributes.description' => 'string',
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
