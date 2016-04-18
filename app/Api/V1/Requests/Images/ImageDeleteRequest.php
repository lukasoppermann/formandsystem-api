<?php

namespace App\Api\V1\Requests\Images;

use App\Api\V1\Requests\DeleteRequest;

class ImageDeleteRequest extends DeleteRequest
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
