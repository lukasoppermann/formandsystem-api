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
            'resourceType' => 'required|in:metadetails',
            'type' => 'required|string',
            'value' => 'required',
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
             'resourceType' => 'required|in:metadetails',
             'resourceId' => 'required|string',
             'type' => 'string',
             'value' => '',
         ];

     }
}
