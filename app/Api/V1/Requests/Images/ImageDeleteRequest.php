<?php

namespace App\Api\V1\Requests\Images;

use App\Api\V1\Requests\Images\ImageRequest;

class ImageDeleteRequest extends ImageRequest
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
