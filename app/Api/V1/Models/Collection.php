<?php

namespace App\Api\V1\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Collection extends BaseModel
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
    protected $fillable = ['id','position','name','slug','type','key'];
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    /**
     * The pages that belong to the collection.
     */
    public function pages()
    {
        return $this->relationshipTrashedFilter($this->hasMany('App\Api\V1\Models\Page', 'collection_id'));
    }
    /**
     * The collections that own this collection
     */
    public function ownedByPages()
    {
        return $this->relationshipTrashedFilter($this->morphedByMany('App\Api\V1\Models\Page', 'collectionable'));
    }
    /**
     * The collections owned by the collection
     */
    public function collections()
    {
        return $this->relationshipTrashedFilter($this->morphToMany('App\Api\V1\Models\Collection', 'collectionable'));
    }
    /**
     * The collections that own this collection
     */
    public function ownedByCollections()
    {
        return $this->relationshipTrashedFilter($this->morphedByMany('App\Api\V1\Models\Collection', 'collectionable'));
    }
    /**
     * The pages that belong to the collection.
     */
    public function fragments()
    {
        return $this->relationshipTrashedFilter($this->morphToMany('App\Api\V1\Models\Fragment', 'fragmentable'));
    }
    /**
     * The collections that own this collection
     */
    public function ownedByFragments()
    {
        return $this->relationshipTrashedFilter($this->morphedByMany('App\Api\V1\Models\Fragment', 'collectionable'));
    }
}
