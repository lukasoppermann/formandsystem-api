<?php

namespace App\Api\V1\Requests;

use App\Api\V1\Requests\ApiRequest;

abstract class DeleteRelationshipRequest extends ApiRequest
{
    protected function rules(){
        return [
            'type'  => 'in:'.implode(',',$this->parentRelationships()).'|required_with:id',
            'id'    => 'string|required_with:type',
            '*.type' => 'in:'.implode(',',$this->parentRelationships()).'|required_with:data.*.id',
            '*.id' => 'string|required_with:data.*.type',
        ];
    }
    /**
     * The relationships the main resource can have
     *
     * @return array
     */
    abstract protected function parentRelationships();
}
