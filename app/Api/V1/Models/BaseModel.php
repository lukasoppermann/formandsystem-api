<?php

namespace App\Api\V1\Models;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class BaseModel extends Model
{
    /**
     * Save a new model and return the instance.
     *
     * @param  array  $attributes
     * @return static
     */
    public static function create(array $attributes = [])
    {
        $model = new static($attributes);
        // add uuid
        if( $model->uuid === true ){
            $model->incrementing = false;
            $model->{$model->getKeyName()} = (string)Uuid::uuid4();
        }

        $model->save();

        return $model;
    }
}
