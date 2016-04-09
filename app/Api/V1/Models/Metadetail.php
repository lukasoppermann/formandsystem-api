<?php

namespace App\Api\V1\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use App\Api\V1\Traits\Uuid;

class Metadetail extends BaseModel
{
    use SoftDeletes;
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
     * The pages that iwn the metadetails.
     */
    public function pages()
    {
        return $this->morphedByMany('App\Api\V1\Models\Page', 'metadetailable');
    }
}
