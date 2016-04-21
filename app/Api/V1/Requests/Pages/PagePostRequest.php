<?php

namespace App\Api\V1\Requests\Pages;

use App\Api\V1\Requests\Pages\PageRequest;

class PagePostRequest extends PageRequest
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
            'type' => 'required|in:pages',
            'attributes.menu_label' => 'required|string',
            'attributes.slug' => 'required|string|alpha_dash',
            'attributes.published' => 'required|boolean',
            'attributes.language' => 'required|string|size:2',
            'attributes.title' => 'string',
            'attributes.description' => 'string'
        ];
    }
}
