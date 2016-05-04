<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Arr;

class SanitizeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        app('validator')->extend('filter', function ($attribute, $value, $parameters, $validator) {
            // get data of field under validation
            $data = [$attribute => $value];
            // get rules for field under validation
            $rules = [$attribute => $parameters];
            // initialize sanitizer
            app('sanitizer')->sanitize($rules, $data);
            // remove original entry from data
            unset($data[$attribute]);
            // convert data into dot array
            $data = Arr::dot($data);
            // get all data unter validation
            $allData = $validator->getData();
            // add sanitized data to all data
            Arr::set($allData, $attribute, $data[$attribute]);
            // add new sanitized data to validator
            app('request')->replace($allData);
            // add new sanitized data to validator
            $validator->setData($allData);
            // return true
            return true;
        });
    }

    public function register()
    {
        app()->register('Rees\Sanitizer\SanitizerServiceProvider');
        app()->register('Mews\Purifier\PurifierServiceProvider');
    }
}
