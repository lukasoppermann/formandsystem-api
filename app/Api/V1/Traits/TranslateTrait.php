<?php

namespace App\Api\V1\Traits;

trait TranslateTrait
{
    /**
     * return a translation
     *
     * @method trans
     *
     * @param  [string] $string
     * @param  [array] $replace
     *
     * @return [string]
     */
    public function trans($string, $replace = []){
        // Translator
        return app('translator')->get($string, $replace);
    }
}
