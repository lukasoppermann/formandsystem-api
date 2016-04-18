<?php

namespace App\Api\V1\Requests\Pages;

use App\Api\V1\Requests\DeleteRequest;

class PageDeleteRequest extends DeleteRequest
{
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
