<?php

namespace App\Api\V1\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class ApiController extends BaseController
{
    // trait
    use \Dingo\Api\Routing\Helpers;

    protected $availableFilters = [];

    public function getFilteredResult($model, $filters){

        foreach((array) $filters as $key => $value){

            if(in_array($key, $this->availableFilters)){
                $model = $model->where($key, $value);
            }

        }

        // no entry exists, throw exception, will be converted to jsonapi response
        if ($model->count() === 0) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
        }

        return $model->get();
    }
}
