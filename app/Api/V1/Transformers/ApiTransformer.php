<?php

namespace App\Api\V1\Transformers;

use League\Fractal\TransformerAbstract;

class ApiTransformer extends TransformerAbstract{

    protected function relationshipsLinks($resource = null)
    {
        if( $resource !== null && isset($this->availableIncludes) && count($this->availableIncludes) > 0 ){
            foreach ($this->availableIncludes as $include) {
                $relationships[$include] = [
                    'links' => [
                        'self'    => $_ENV['API_DOMAIN'].'/'.$resource.'/relationships/'.$include,
                        'related' => $_ENV['API_DOMAIN'].'/'.$resource.'/'.$include
                    ]
                ];
            }
            return $relationships;
        }
        // retur empty array if no available includes exist
        return [];

    }

    protected function decode($value){
        if(is_string($value) && json_decode($value) !== null){
            return json_decode($value);
        }
        return $value;
    }

    protected function encode($value){
        if(is_array($value) || is_object($value)){
            return json_encode($value);
        }
        return $value;
    }

}
