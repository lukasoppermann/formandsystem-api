<?php

namespace App\Api\V1\Requests\Uploads;

use App\Api\V1\Requests\Uploads\UploadRequest;

class UploadPutRequest extends UploadRequest
{
    /**
     * make this a file request
     *
     * @var [bool]
     */
    protected $fileRequest = TRUE;
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
