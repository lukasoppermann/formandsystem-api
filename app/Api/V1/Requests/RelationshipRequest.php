<?php

namespace App\Api\V1\Requests;

use App\Api\V1\Requests\ResourceRequest;

abstract class RelationshipRequest extends ResourceRequest
{
    /**
     * Retuns rules for a relationship
     *
     * @method rules
     *
     * @return array
     */
    protected function rules(){
        return [
            'type'  => 'in:'.implode(',',$this->relationships()).'|required_with:id',
            'id'    => 'string|required_with:type',
            '*.type' => 'in:'.implode(',',$this->relationships()).'|required_with:data.*.id',
            '*.id' => 'string|required_with:data.*.type',
        ];
    }
}
