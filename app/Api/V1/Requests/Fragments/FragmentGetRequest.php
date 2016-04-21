<?php

namespace App\Api\V1\Requests\Fragments;

use App\Api\V1\Requests\Fragments\FragmentRequest;

class FragmentGetRequest extends FragmentRequest
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
     * The filters that are allowed in requests
     *
     * @return array
     */
    protected function filters(){
        return[
            'type',
            'name'
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
