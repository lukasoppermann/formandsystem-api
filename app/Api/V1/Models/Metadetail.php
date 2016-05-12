<?php

namespace App\Api\V1\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Metadetail extends BaseModel
{
    use SoftDeletes;
    /**
     * set connection for this model
     *
     * @var string
     */
    protected $connection = 'user';
    /**
     * If uuid is used instead of autoincementing id
     *
     * @var bool
     */
    protected $uuid = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id','type','value'];
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
    public function ownedByPages()
    {
        return $this->relationshipTrashedFilter($this->morphedByMany('App\Api\V1\Models\Page', 'metadetailable'));
    }
    /**
     * The pages that belong to the fragment.
     */
    public function ownedByFragments()
    {
        return $this->relationshipTrashedFilter($this->morphedByMany('App\Api\V1\Models\Fragment', 'metadetailable'));
    }
    /**
     * The images that belong to the fragment.
     */
    public function images()
    {
        return $this->relationshipTrashedFilter($this->morphToMany('App\Api\V1\Models\Image', 'imageable'));
    }
    /**
     * The pages that belong to the fragment.
     */
    public function ownedByImages()
    {
        return $this->relationshipTrashedFilter($this->morphedByMany('App\Api\V1\Models\Image', 'metadetailable'));
    }
}
