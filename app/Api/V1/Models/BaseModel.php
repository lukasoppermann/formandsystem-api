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
    /**
     * get the fields accepted to be send to model
     *
     * @method acceptedFields
     *
     * @return [array]
     */
    public function acceptedFields(){
        // get mode fields
        $fields = $this->getFillable();
        // remove id
        array_splice($fields, array_search('id', $fields), 1);
        // add soft delete is_trashed
        if($this->forceDeleting === false){
            $fields[] = 'is_trashed';
        }
        // return fields
        return $fields;
    }
    /**
     * find model including softDeleted
     *
     * @method findWithTrashed
     *
     * @param  [uuid]          $id
     * @param  [bool]          $withTrashed
     *
     * @return [model]
     */
    public function findWithTrashed($id, $withTrashed = true)
    {
        if($withTrashed === true){
            return $this->withTrashed()->where('id',$id)->first();
        }

        return $this->where('id',$id)->first();
    }
    /**
     * trash or restore from trash
     *
     * @method setTrashed
     *
     * @param  [type]     $model
     * @param  bool       $is_trashed
     */
    public function setTrashed($is_trashed = false){
        if($this->forceDeleting === false){
            // softDelete if is_trashed is set to true
            if($is_trashed === true){
                $this->delete();
            }
            // restore if is_trashed is set to false
            $this->restore();
        }
    }
    /**
     * return model with trashed items or not
     *
     * @method withTrashed
     *
     * @param  bool       $withTrashed
     */
    public function allWithTrashed($withTrashed = true){
        if($this->forceDeleting === false){
            // show softDeleted
            if($withTrashed === true){
                $this->withTrashed();
            }
        }
    }
}
