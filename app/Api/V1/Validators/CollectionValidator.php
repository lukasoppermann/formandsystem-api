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
            'type' => 'required|in:collections',
            'attributes' => [
                'name' => 'required|string',
                'slug' => 'required|string|alpha_dash',
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
             'id' => 'required|string',
             'type' => 'required|in:collections',
             'attributes' => [
                 'name' => 'string',
                 'slug' => 'string|alpha_dash',
             ]
         ];

     }
}
