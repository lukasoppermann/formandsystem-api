<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Models\Collection;
use App\Api\V1\Transformers\CollectionTransformer;
use App\Api\V1\Transformers\PageTransformer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
/**
 * Collection resource representation.
 *
 * @Resource("Collections", uri="/collections")
 */
class CollectionsController extends ApiController
{
    /**
     * The filters that are allowed in requests
     *
     * @var array
     */
    protected $availableFilters = [
        'slug'
    ];
    /**
     * The relationships a resource can have
     *
     * @var array
     */
    // protected $relationships = [
    //     'pages',
    //     'collections'
    // ];
    /**
     * The resources name
     *
     * @var array
     */
    protected $resource = 'collections';
}
