<?php

namespace App\Api\V1\Validators;

class ImageValidator extends ApiValidator{
    /**
     * rules Post
     *
     * @method rulesPost
     *
     * @return array $rules
     */
    protected function rulesPost(){

        return [
            'type' => 'required|in:images',
            'attributes' => [
                'link' => 'url|required',
                'slug' => 'string|required',
                'bytesize' => 'int|required',
                'width' => 'int|required',
                'height' => 'int|required',
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
             'type' => 'required|in:images',
             'attributes' => [
                 'link' => 'url|required_if:bytesize,width,height',
                 'slug' => 'string',
                 'bytesize' => 'int|required_if:link,width,height',
                 'width' => 'int|required_if:link,bytesize,height',
                 'height' => 'int|required_if:link,bytesize,width',
             ]
         ];

     }
}
