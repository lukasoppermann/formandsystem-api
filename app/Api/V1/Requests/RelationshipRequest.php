<?php

namespace App\Api\V1\Requests;

use App\Api\V1\Requests\AbstractRequest;

class RelationshipRequest extends AbstractRequest
{
    use RequestAuthorization;
    /**
     * Retuns rules for a relationship
     *
     * @method rules
     *
     * @return array
     */
    public function rules(){
        $relationships = array_map(function($item){
            return str_replace('ownedBy','',$item);
        },$this->relationships());

        return [
            'data.type'  => 'in:'.implode(',',$relationships).'|required_with:id',
            'data.id'    => 'string|required_with:type',
            'data.*.type' => 'in:'.implode(',',$relationships).'|required_with:data.*.id',
            'data.*.id' => 'string|required_with:data.*.type',
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
        $parentRequest = "App\Api\V1\Requests\\".$resourceName.'Request';
        // return relationships
        return get_class_vars($parentRequest)['relationships'];
    }
    /**
     * override to get scope for request
     *
     * @method getScope
     *
     * @return [array]
     */
    public function getScope($method){
        $resourceName = ucfirst(substr($this->request->segment(1),0,-1));
        $parentRequest = "App\Api\V1\Requests\\".$resourceName.'Request';
        // return relationships
        $scopes = get_class_vars($parentRequest)['scopes'];
        // return scope
        if( isset($scopes) && is_array($scopes) && isset($scopes[$method]) ){
            return $scopes[$method];
        }
        // scope is disabled
        if($scopes === false){
            return [];
        }
        // log error
        \LOG::error('No scope defined for "'.class_basename($resourceName.'Request').'".');
        throw new \Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException(null, 'Failed to authorize the request due to an internal error. Please notify support@formandsystem.com.');
    }
}
