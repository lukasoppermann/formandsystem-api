<?php

namespace App\Api\V1\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends BaseModel
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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id','link','slug','filename','bytesize','height','width'];
    /**
     * The images that belong to the image.
     */
    public function images()
    {
        return $this->relationshipTrashedFilter($this->morphToMany('App\Api\V1\Models\Image', 'imageable'));
    }
    /**
     * The images that belong to the image.
     */
    public function ownedByImages()
    {
        return $this->relationshipTrashedFilter($this->morphedByMany('App\Api\V1\Models\Image', 'imageable'));
    }
    /**
     * The fragments that belong to the image.
     */
    public function ownedByFragments()
    {
        return $this->relationshipTrashedFilter($this->morphedByMany('App\Api\V1\Models\Fragment', 'imageable'));
    }
    /**
     * The fragments that belong to the fragment.
     */
    public function ownedByCollections()
    {
        return $this->relationshipTrashedFilter($this->morphedByMany('App\Api\V1\Models\Collection', 'imageable'));
    }
}
