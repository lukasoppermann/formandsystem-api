<?php

namespace App\Api\V1\Validators;

class CollectionValidator extends ApiValidator{
    /**
     * rules Post
     *
     * @method rulesPost
     *
     * @return array $rules
     */
    protected function rulesPost(){

        return [
            'resourceType' => 'required|in:collections',
            'name' => 'required|string',
            'slug' => 'required|string',
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
             'resourceType' => 'required|in:collections',
             'resourceId' => 'required|string',
             'name' => 'string',
             'slug' => 'string',
         ];

     }
}
