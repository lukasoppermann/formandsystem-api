<?php

namespace App\Api\V1\Requests\Metadetails;

use App\Api\V1\Requests\PostRequest;

class MetadetailPostRequest extends PostRequest
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
            'type' => 'required|in:metadetails',
            'attributes' => [
                'type' => 'required|string|alpha_dash',
                'value' => 'required|string_or_array',
            ]
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
