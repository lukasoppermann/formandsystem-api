<?php

namespace App\Api\V1\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends BaseModel
{
    use SoftDeletes;
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
        return $this->morphToMany('App\Api\V1\Models\Fragment', 'fragmentable');
    }
    /**
     * The collection that owns the page.
     */
    public function collections()
    {
        return $this->belongsToMany('App\Api\V1\Models\Collection');
    }
    /**
     * The metadetails that belongs to the page.
     */
    public function metadetails()
    {
        return $this->morphToMany('App\Api\V1\Models\Metadetail', 'metadetailable');
    }
    /**
     * The pages that belongs to the page.
     */
    public function pages()
    {
        return $this->hasMany('App\Api\V1\Models\Page', 'parent_id');
    }
    /**
     * The pages that belongs to the page.
     */
    public function ownedbypages()
    {
        return $this->belongsTo('App\Api\V1\Models\Page', 'parent_id');
    }
}
