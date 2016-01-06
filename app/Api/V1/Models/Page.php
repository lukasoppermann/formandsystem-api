<?php

namespace App\Api\V1\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use SoftDeletes;
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;
    /**
     * Indicates if the model should force an auto-incrementeing id.
     *
     * @var bool
     */
    public $incrementing = false;
    /**
     * The fragments that belong to the page.
     */
    public function fragments()
    {
        return $this->morphToMany('App\Api\V1\Models\Fragment', 'fragmentable');
    }
    /**
     * The collection that owns the page.
     */
    public function collections()
    {
        return $this->belongsToMany('App\Api\V1\Models\Collection');
    }
}
