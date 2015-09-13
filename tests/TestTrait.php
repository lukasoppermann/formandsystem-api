<?php

namespace Lukasoppermann\Testing;

use PHPUnit_Framework_Assert as Assertion;
use Illuminate\Support\Facades\Validator;

trait TestTrait
{
    //
    protected function assertValidArray(array $rules, array $resourceData)
    {
        // set all rules to required
        foreach($rules as $key => $rule){
            $rules[$key] = $key.'|required';
        }

        $validator = Validator::make($resourceData, $rules);
        if( $validator->passes() === false ) {

            foreach($validator->messages()->toArray() as $error){
                $errors[] =  "\e[1;31m× \033[0m".$error[0];
            }

            Assertion::fail(implode(PHP_EOL,$errors));
        }
    }

}
