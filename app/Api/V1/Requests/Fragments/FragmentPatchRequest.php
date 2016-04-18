<?php

namespace App\Api\V1\Requests\Fragments;

use App\Api\V1\Requests\PatchRequest;

class FragmentPatchRequest extends PatchRequest
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
            'id' => 'required|string',
            'type' => 'required|in:fragments',
            'attributes.name' => 'string',
            'attributes.type' => 'string',
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
