<?php

namespace Lukasoppermann\Testing\Traits;

use PHPUnit_Framework_Assert as Assertion;
use Illuminate\Support\Facades\Validator;

trait TestTrait
{
    /*
     * Validation errors
     */
    private $errors = [];
    /*
     * Validate an array against predefined rules.
     */
    protected function assertValidArray(array $rules, array $resourceData)
    {
        $this->validateArray($rules, $resourceData);

        if (count($this->errors) >= 1) {
            Assertion::fail(implode(PHP_EOL, $this->errors));
        }
    }

    /*
     * Get validation rules and run validator
     */
    protected function validateArray($rules, $resourceData)
    {
        // set all rules to required
        foreach ($rules as $key => $rule) {
            // if the attribute has no children, validate it
            if (!is_array($rule)) {
                $rules[$key] = $rule;
                // add required if not explicitly set to not required
                if(!strpos($rule,'not_required')){
                    $rules[$key] = $rules[$key].'|required';
                }
                $rules[$key] = str_replace('|not_required','',$rules[$key]);
            // if the attribute has children, do a sub-loop
            } else {
                $rules[$key] = 'required';
                if (array_key_exists($key, $resourceData)) {
                    $this->validateArray($rule, $resourceData[$key]);
                }
            }

        }
        // run validator
        $validator = Validator::make($resourceData, $rules);
        // store errors
        foreach ($validator->messages()->toArray() as $error) {
            $this->errors[] =  "\e[1;31m× \033[0m".$error[0];
        }
    }
}
