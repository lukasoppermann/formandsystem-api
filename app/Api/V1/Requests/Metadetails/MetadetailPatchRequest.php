<?php

namespace App\Api\V1\Requests\Metadetails;

use App\Api\V1\Requests\PatchRequest;

class MetadetailPatchRequest extends PatchRequest
{
    /**
     * The relationships a resource can have
     *
     * @return array
     */
     protected function relationships(){
         return[
             'pages'
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
            'attributes.type'   => 'string|alpha_dash|not',
            'attributes.value'  => 'string_or_array'
        ];
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
