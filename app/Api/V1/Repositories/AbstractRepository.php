<?php

namespace App\Api\V1\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Collection;
/**
 * Base Repository with general methods
 *
 **/
abstract class AbstractRepository implements Repository
{
    /**
     * find by id or fail with Exception
     * @method findOrFail
     * @param  Illuminate\Database\Eloquent\Model    $model
     * @param  string                                $id
     * @return \Illuminate\Database\Eloquent\Builder|static
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function findOrFail(Model $model, $id)
    {
        try{
            return $model->findOrFail($id);
        }catch(ModelNotFoundException $e){
            throw new NotFoundHttpException(app('translator')->trans('errors.resource_missing'));
        }
    }

    protected function applyFilter(Model $model, Collection $filter)
    {
        // with trashed items
        if($filter->get('with_trashed') === true){
            $model = $model->withTrashed();
        }
        // only trashed items
        if($filter->get('only_trashed') === true){
            $model = $model->onlyTrashed();
        }
        // apply filters
        foreach($filter->get('filter') as $key => $values){
            $model = $model->whereIn($key, $values);
        }

        return $model;
    }
}
