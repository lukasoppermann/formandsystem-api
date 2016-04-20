<?php

namespace App\Api\V1\Requests\Pages;

use App\Api\V1\Requests\GetRelationshipRequest;

class PageGetRelationshipRequest extends GetRelationshipRequest
{
    /**
     * check if request is authorized
     *
     * @method authorize
     *
     * @return array
     */
    protected function authorize(){
        return true;
    }

}
