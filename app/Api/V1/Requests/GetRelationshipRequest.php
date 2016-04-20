<?php

namespace App\Api\V1\Requests;

use App\Api\V1\Requests\ApiRequest;

abstract class GetRelationshipRequest extends ApiRequest
{
    protected function rules(){
        return [];
    }
}
