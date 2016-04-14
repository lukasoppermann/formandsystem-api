<?php

namespace App\Api\V1\Validators;

class PageValidator extends ApiValidator{
    /**
     * rules Post
     *
     * @method rulesPost
     *
     * @return array $rules
     */
    protected function rulesPost(){

        return [
            'type' => 'required|in:pages',
            'attributes' => [
                'menu_label' => 'required|string',
                'slug' => 'required|string|alpha_dash',
                'published' => 'required|boolean',
                'language' => 'required|string|size:2',
                'title' => 'string',
                'description' => 'string',
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
            'type' => 'required|in:pages',
            'id' => 'required|string',
            'attributes' => [
                'menu_label' => 'string',
                'slug' => 'string|alpha_dash',
                'published' => 'boolean',
                'language' => 'string|size:2',
                'title' => 'string',
                'description' => 'string',
            ]
        ];

     }
}
