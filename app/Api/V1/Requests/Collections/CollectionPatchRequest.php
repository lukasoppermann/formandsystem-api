<?php

namespace App\Api\V1\Requests\Collections;

use App\Api\V1\Requests\PatchRequest;

class CollectionPatchRequest extends PatchRequest
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
            'id' => 'required|string',
            'type' => 'required|in:collections',
            'attributes.name' => 'string',
            'attributes.slug' => 'string|alpha_dash',
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
