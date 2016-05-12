<?php

namespace App\Api\V1\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends BaseModel
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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'menu_label',
        'title',
        'description',
        'published',
        'slug',
        'language',
    ];
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
        return $this->relationshipTrashedFilter($this->morphToMany('App\Api\V1\Models\Fragment', 'fragmentable'));
    }
    /**
     * The collection that owns the page.
     */
    public function collections()
    {
        return $this->relationshipTrashedFilter($this->morphToMany('App\Api\V1\Models\Collection', 'collectionable'));
    }
    /**
     * The Collections that belongs to the page.
     */
    public function ownedByCollections()
    {
        return $this->relationshipTrashedFilter($this->morphedByMany('App\Api\V1\Models\Collection', 'pageable'));
    }
    /**
     * The metadetails that belongs to the page.
     */
    public function metadetails()
    {
        return $this->relationshipTrashedFilter($this->morphToMany('App\Api\V1\Models\Metadetail', 'metadetailable'));
    }
    /**
     * The pages that belongs to the page.
     */
    public function pages()
    {
        return $this->relationshipTrashedFilter($this->morphToMany('App\Api\V1\Models\Page', 'pageable'));
    }
    /**
     * The pages that belongs to the page.
     */
    public function ownedByPages()
    {
        return $this->relationshipTrashedFilter($this->morphedByMany('App\Api\V1\Models\Page', 'pageable'));
    }
}
