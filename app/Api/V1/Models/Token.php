<?php

namespace App\Api\V1\Models;

class Token extends BaseModel
{
    protected $table = "oauth_access_tokens";
    /**
     * If uuid is used instead of autoincementing id
     *
     * @var bool
     */
    protected $uuid = false;
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
    protected $fillable = ['id','secret','name'];
}
