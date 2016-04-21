<?php

namespace App\Api\V1\Requests\Pages;

use App\Api\V1\Requests\Pages\PageRequest;

class PageDeleteRequest extends PageRequest
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
