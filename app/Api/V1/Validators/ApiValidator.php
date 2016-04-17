<?php

namespace App\Api\V1\Validators;

use Illuminate\Support\Facades\Validator;

abstract class ApiValidator{
    /**
     * validate input
     *
     * @method validateInput
     */
    protected function validate($input, $rules){
        if(!is_array($input)){
            throw new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
        }
        // prepare rules
        $rules = $this->prepareRules($rules);
        // run validation
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
     * validate Patch
     *
     * @method validatePatch
     */
    public function validatePatch($input){
        $rules = $this->rulesPatch();
        return $this->validate($input, $rules);
    }
    /**
     * prepare validation rules
     *
     * @method prepareRules
     */
    public function prepareRules($rules){
        $output = [];
        foreach($rules as $key => $rule){
            if(!is_array($rule)){
                $output[$key] = $rule;
            }
            if(is_array($rule)){
                foreach($rule as $k => $r){
                    $output[$key.'.'.$k] = $r;
                }
            }
        }
        return $output;
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
