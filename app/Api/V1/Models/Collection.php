<?php

namespace App\Api\V1\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Collection extends BaseModel
{
    use SoftDeletes;
    /**
     * If uuid is used instead of autoincementing id
     *
     * @var bool
     */
    protected $uuid = true;
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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id','name','slug'];
    /**
     * The pages that belong to the collection.
     */
    public function pages()
    {
        return $this->belongsToMany('App\Api\V1\Models\Page');
    }
}
