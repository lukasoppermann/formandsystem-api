<?php

namespace App\Api\V1\Transformers;

use League\Fractal\TransformerAbstract;

class ApiTransformer extends TransformerAbstract{
    
    public function relationshipsLinks($resource = null)
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

}
