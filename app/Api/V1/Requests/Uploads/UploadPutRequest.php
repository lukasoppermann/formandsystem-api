<?php

namespace App\Api\V1\Requests\Uploads;

use Illuminate\Http\Request;
use App\Api\V1\Requests\Uploads\UploadRequest;

class UploadPutRequest extends UploadRequest
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
        return [

        ];
    }
}
