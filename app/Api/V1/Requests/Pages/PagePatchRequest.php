<?php

namespace App\Api\V1\Requests\Pages;

use App\Api\V1\Requests\Pages\PageRequest;

class PagePatchRequest extends PageRequest
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
            'id' => 'required|string',
            'attributes.menu_label' => 'string',
            'attributes.slug' => 'string|alpha_dash',
            'attributes.published' => 'boolean',
            'attributes.language' => 'string|size:2',
            'attributes.title' => 'string',
            'attributes.description' => 'string',
        ];
    }
}
