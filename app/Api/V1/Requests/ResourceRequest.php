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
    public function scopes(){
        // return method specific rules
        if(isset($this->scopes) && is_array($this->scopes)){
            return $this->scopes;
        }
        // or empty array if none are set
        return [];
    }
    /**
     * The relationships the main resource can have
     *
     * @method relationships
     *
     * @return array
     */
     /**
      * The relationships a resource can have
      *
      * @return array
      */
      public function relationships(){
          // return method specific rules
          if(isset($this->relationships) && is_array($this->relationships)){
              return $this->relationships;
          }
          // or empty array if none are set
          return [];
      }
    /**
     * Retuns rules
     *
     * @method rules
     *
     * @return array
     */
    protected function rules(){
       // get method
       $method = strtolower($this->request->getMethod());
       // return method specific rules
       if(isset($this->rules[$method])){
           return $this->rules[$method];
       }
       // or empty array if none are set
       return [];
    }
}
