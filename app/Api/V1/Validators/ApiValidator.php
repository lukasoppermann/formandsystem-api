<?php

namespace App\Api\V1\Validators;

use Illuminate\Support\Facades\Validator;

abstract class ApiValidator{
    /**
     * Indicates if updates with only a part of the content are allowed
     *
     * @var bool
     */
    protected $partialUpdate = true;
    /**
     * validate input
     *
     * @method validateInput
     */
    protected function validate($input, $rules){
        $validator = Validator::make($input, $rules);
        // return errors
        if( $validator->fails() ){
            foreach ($validator->errors()->toArray() as $key => $value) {
                $errorMessage[] = $value[0];
            }
            return implode(' ',$errorMessage);
        }

        return false;
    }
    /**
     * validate Post
     *
     * @method validatePost
     */
    public function validatePost($input){
        $rules = $this->rulesPost();
        return $this->validate($input, $rules);
    }
    /**
     * validate Post
     *
     * @method validatePost
     */
    public function validatePatch($input){
        $rules = $this->rulesPatch();
        return $this->validate($input, $rules);
    }
    /**
     * rules Post
     *
     * @method rulesPost
     *
     * @return array $rules
     */
    abstract protected function rulesPost();
    /**
     * rules Patch
     *
     * @method rulesPatch
     *
     * @return array $rules
     */
    abstract protected function rulesPatch();
}
