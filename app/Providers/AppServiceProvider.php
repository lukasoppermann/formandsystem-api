<?php

namespace App\Providers;

use Validator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if(env('APP_ENV') == 'testing') {
            ini_set('memory_limit', '2G');
        }
        // extend the validator
        $this->extendValidator();
    }
    /**
     * extend the validator
     *
     * @method extendValidator
     *
     * @return [void]
     */
    protected function extendValidator(){
        // item to be string or array
        Validator::extend('string_or_array', function($attribute, $value, $parameters, $validator) {
            return (is_string($value) || is_array($value));
        });
        // array to only have certain attribtues
        Validator::extend('array_has_only', function($attribute, $value, $parameters, $validator) {
            foreach($value as $key => $v){
                if(!in_array($key,$parameters)){
                    return false;
                }
            }
            return true;
        });
        Validator::replacer('array_has_only', function($message, $attribute, $rule, $parameters) {
            return str_replace(':array_values',implode($parameters,', '),$message);
        });
    }
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
