<?php

namespace App\Api\V1\Requests\Fragments;

use App\Api\V1\Requests\GetRequest;

class FragmentGetRequest extends GetRequest
{
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
