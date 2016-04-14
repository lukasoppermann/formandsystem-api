<?php

namespace App\Api\V1\Validators;

class MetadetailValidator extends ApiValidator{
    /**
     * rules Post
     *
     * @method rulesPost
     *
     * @return array $rules
     */
    protected function rulesPost(){

        return [
            'type' => 'required|in:metadetails',
            'attributes' => [
                'type' => 'required|string|alpha_dash',
                'value' => 'required',
            ]
        ];

    }
    /**
     * rules Patch
     *
     * @method rulesPatch
     *
     * @return array $rules
     */
     protected function rulesPatch(){

         return [
             'type' => 'required|in:metadetails',
             'id' => 'required|string',
             'attributes' => [
                 'type' => 'string|alpha_dash',
                 'value' => '',
             ]
         ];

     }
}
