<?php

namespace App\Api\V1\Validators;

class FragmentValidator extends ApiValidator{
    /**
     * rules Post
     *
     * @method rulesPost
     *
     * @return array $rules
     */
    protected function rulesPost(){

        return [
            'type' => 'required|in:fragments',
            'attributes' => [
                'name' => 'string',
                'type' => 'required|string',
                'data' => '',
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
             'type' => 'required|in:fragments',
             'attributes' => [
                 'name' => 'string',
                 'type' => 'string',
                 'data' => '',
             ]
         ];

     }
}
