<?php

namespace App\Api\V1\Requests\Metadetails;

use App\Api\V1\Requests\DeleteRequest;

class MetadetailDeleteRequest extends DeleteRequest
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
