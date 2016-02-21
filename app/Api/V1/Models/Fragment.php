<?php

namespace App\Api\V1\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fragment extends Model
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
     * The pages that belong to the fragment.
     */
    public function pages()
    {
        return $this->morphedByMany('App\Api\V1\Models\Page', 'fragmentable');
    }
    /**
     * The fragments that belong to the fragment.
     */
    public function fragments()
    {
        // return $this->belongsToMany('App\Api\V1\Models\Fragment');
        return $this->morphToMany('App\Api\V1\Models\Fragment', 'fragmentable');
    }
}