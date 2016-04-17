<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Models\Image;
use App\Api\V1\Transformers\ImageTransformer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class ImagesController extends ApiController
{
    /**
     * The relationships a resource can have
     *
     * @var array
     */
    protected $relationships = [
        'images',
        'fragments',
    ];
    /**
     * The filters that are allowed in requests
     *
     * @var array
     */
    protected $availableFilters = [
        'slug'
    ];
    /**
     * The resources name
     *
     * @var array
     */
    protected $resource = 'images';

}
