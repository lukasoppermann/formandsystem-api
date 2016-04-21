<?php

namespace App\Api\V1\Requests\Fragments;

use App\Api\V1\Requests\Fragments\FragmentRequest;

class FragmentDeleteRequest extends FragmentRequest
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

    }
}
