<?php

namespace App\Api\V1\Requests;

use App\Api\V1\Requests\ApiRequest;

class RelationshipRequest extends ApiRequest
{
    /**
     * Retuns rules for a relationship
     *
     * @method rules
     *
     * @return array
     */
    public function rules(){
        return [
            'type'  => 'in:'.implode(',',$this->relationships()).'|required_with:id',
            'id'    => 'string|required_with:type',
            '*.type' => 'in:'.implode(',',$this->relationships()).'|required_with:data.*.id',
            '*.id' => 'string|required_with:data.*.type',
        ];
    }
    /**
     * The scopes needed to do this request
     *
     * @return array
     */
    public function scopes(){
        return [
            $this->request->segment(1).'.'.strtolower($this->request->method())
        ];
    }
    /**
     * The relationships the main resource can have
     *
     * @method relationships
     *
     * @return array
     */
    public function relationships(){
        $resourceName = ucfirst(substr($this->request->segment(1),0,-1));
        $parentRequest = "App\Api\V1\Requests\\".$resourceName.'s\\'.$resourceName.'Request';
        // return relationships
        return get_class_vars($parentRequest)['relationships'];
    }
    /**
     * filters available for the request
     *
     * @method filters
     *
     * @return array
     */
    protected function filters(){
        return [];
    }
}
