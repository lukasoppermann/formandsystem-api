<?php

namespace App\Api\V1\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Fragment extends BaseModel
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
    protected $fillable = ['id','position','type','name','data','meta'];
    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'meta' => 'collection',
    ];
    /**
     * The pages that belong to the fragment.
     */
    public function ownedByPages()
    {
        return $this->relationshipTrashedFilter($this->morphedByMany('App\Api\V1\Models\Page', 'fragmentable'));
    }
    /**
     * The fragments that belong to the fragment.
     */
    public function fragments()
    {
        return $this->relationshipTrashedFilter($this->morphToMany('App\Api\V1\Models\Fragment', 'fragmentable'));
    }
    /**
     * The fragments that belong to the fragment.
     */
    public function ownedByFragments()
    {
        return $this->relationshipTrashedFilter($this->morphedByMany('App\Api\V1\Models\Fragment', 'fragmentable'));
    }
    /**
     * The images that belong to the fragment.
     */
    public function images()
    {
        return $this->relationshipTrashedFilter($this->morphToMany('App\Api\V1\Models\Image', 'imageable'));
    }
    /**
     * The images that belong to the fragment.
     */
    public function collections()
    {
        return $this->relationshipTrashedFilter($this->morphToMany('App\Api\V1\Models\Collection', 'collectionable'));
    }
    /**
     * The fragments that belong to the fragment.
     */
    public function ownedByCollections()
    {
        return $this->relationshipTrashedFilter($this->morphedByMany('App\Api\V1\Models\Collection', 'fragmentable'));
    }
}
