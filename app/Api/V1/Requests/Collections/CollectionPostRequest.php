<?php

namespace App\Api\V1\Requests\Collections;

use App\Api\V1\Requests\Collections\CollectionRequest;

class CollectionPostRequest extends CollectionRequest
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
            'type' => 'required|in:collections',
            'attributes.name' => 'required|string',
            'attributes.slug' => 'required|string|alpha_dash',
        ];
    }
}
