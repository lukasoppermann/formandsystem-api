<?php

namespace App\Api\V1\Requests\Pages;

use App\Api\V1\Requests\GetRequest;

class PageGetRequest extends GetRequest
{
    /**
     * The filters that are allowed in requests
     *
     * @return array
     */
    protected function filters(){
        return[
            'slug'
        ];
    }
    /**
     * The relationships a resource can have
     *
     * @return array
     */
     protected function relationships(){
         return[
             'collections',
             'metadetails',
             'fragments',
             'metadetails'
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
