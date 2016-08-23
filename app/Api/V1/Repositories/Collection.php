<?php

namespace App\Api\V1\Repositories;

use App\Api\V1\Models\Collection as Model;

/**
 * Collection repository
 *
 * @return Eloquent Collection
 */
class Collection extends AbstractRepository{

    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function all(Array $parameters = []){
        $model = $this->applyFilter($this->model, collect($parameters));
        return $model->get();
    }

    public function getById($id, Array $parameters = []){
        $model = $this->applyFilter($this->model, collect($parameters));
        return $this->findOrFail($model, $id);
    }
}
