<?php

namespace App\Api\V1\Requests\Fragments;

use App\Api\V1\Requests\DeleteRequest;

class FragmentDeleteRequest extends DeleteRequest
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
