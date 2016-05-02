<?php

namespace App\Api\V1\Requests\Fragments;

use App\Api\V1\Requests\Fragments\FragmentRequest;

class FragmentPatchRequest extends FragmentRequest
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
            'type' => 'required|in:fragments',
            'attributes.name' => 'string',
            'attributes.type' => 'string',
            'attributes.data' => '',
            'attributes.is_trashed' => 'boolean',
        ];
    }
}
