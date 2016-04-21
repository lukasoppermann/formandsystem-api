<?php

namespace App\Api\V1\Requests\Metadetails;

use App\Api\V1\Requests\Metadetails\MetadetailRequest;

class MetadetailGetRequest extends MetadetailRequest
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
            'type'
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
