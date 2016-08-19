<?php

namespace Lukasoppermann\Testing\Traits;

use Validator;

trait ValidationTrait
{
    /**
     * extend Validator
     */
    protected function extendValidator(){
        Validator::extend('stringOrArray', function($attribute, $value, $parameters, $validator) {
            return (is_string($value) || is_array($value));
        });
    }
    /**
     * validate against blueprint
     *
     * @method validate
     */
    public function assertValid($input, $rules){
        // get rules
        $rules = $this->prepareRules($rules);
        // validate
        $this->extendValidator();
        $validation = Validator::make($input, $rules);
        // merge errors to string
        $errors = "";
        foreach($validation->messages()->toArray() as $err){
            foreach($err as $msg){
                $errors .= "× ".$msg."\n";
            }
        }
        // ASSERTION
        $this->assertFalse($validation->fails(), $errors);
    }
    /**
     * prepare validation rules
     *
     * @method prepareRules
     */
    protected function prepareRules($rules){
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
}
