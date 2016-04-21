<?php

namespace App\Api\V1\Requests\Collections;

use App\Api\V1\Requests\Collections\CollectionRequest;

class CollectionPatchRequest extends CollectionRequest
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
            'type' => 'required|in:collections',
            'attributes.name' => 'string',
            'attributes.slug' => 'string|alpha_dash',
        ];
    }
}
