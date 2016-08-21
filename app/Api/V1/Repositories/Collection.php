<?php

namespace App\Api\V1\Repositories;

use App\Api\V1\Models\Collection as Model;

/**
 * Collection repository
 *
 * @return Eloquent Collection
 */
class Collection implements Repository{

    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(Array $parameters = []){
        return $this->model->all();
    }

    public function getById($id){
        return $this->model->findOrFail($id);
    }
}
