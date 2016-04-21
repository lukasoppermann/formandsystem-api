<?php

namespace App\Api\V1\Requests\Pages;

use App\Api\V1\Requests\Pages\PageRequest;

class PageGetRequest extends PageRequest
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
            'slug'
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
