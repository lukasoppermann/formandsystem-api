<?php

namespace App\Api\V1\Requests;

use App\Api\V1\Requests\ApiRequest;

abstract class ResourceRequest extends ApiRequest
{
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
    /**
     * Retuns needed scopes to perform a request
     *
     * @method scopes
     *
     * @return array
     */
    abstract protected function scopes();
    /**
     * The relationships the main resource can have
     *
     * @method relationships
     *
     * @return array
     */
    abstract public function relationships();
}
