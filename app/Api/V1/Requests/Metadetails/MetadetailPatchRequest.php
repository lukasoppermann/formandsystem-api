<?php

namespace App\Api\V1\Requests\Metadetails;

use App\Api\V1\Requests\Metadetails\MetadetailRequest;

class MetadetailPatchRequest extends MetadetailRequest
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
            'id'                => 'required|string',
            'type'              => 'required|in:metadetails',
            'attributes.type'   => 'string|alpha_dash',
            'attributes.value'  => 'string_or_array'
        ];
    }
}
