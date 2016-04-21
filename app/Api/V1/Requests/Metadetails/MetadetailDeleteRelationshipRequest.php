<?php

namespace App\Api\V1\Requests\Metadetails;

use App\Api\V1\Requests\Metadetails\MetadetailRelationshipRequest;

class MetadetailDeleteRelationshipRequest extends MetadetailRelationshipRequest
{
    /**
     * The scopes needed to do this request
     *
     * @return array
     */
    protected function scopes(){
        return [

        ];
    }
}
