<?php

namespace App\Api\V1\Requests\Metadetails;

use App\Api\V1\Requests\Metadetails\MetadetailRequest;

class MetadetailPostRequest extends MetadetailRequest
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
            'type'              => 'required|in:metadetails',
            'attributes.type'   => 'required|string|alpha_dash',
            'attributes.value'  => 'required|string_or_array'
        ];
    }
}
