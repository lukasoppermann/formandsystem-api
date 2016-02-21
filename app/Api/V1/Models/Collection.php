<?php

namespace App\Api\V1\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    /**
     * Indicates if the model should force an auto-incrementeing id.
     *
     * @var bool
     */
    public $incrementing = false;
    /**
     * The pages that belong to the collection.
     */
    public function pages()
    {
        return $this->belongsToMany('App\Api\V1\Models\Page');
    }
}