<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Models\Page;
use App\Api\V1\Transformers\PageTransformer;
use App\Api\V1\Transformers\CollectionTransformer;
use App\Api\V1\Transformers\FragmentTransformer;
use League\Fractal\Serializer\DataArraySerializer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class PagesController extends ApiController
{
    /**
     * The filters that are allowed in requests
     *
     * @var array
     */
    protected $availableFilters = [
        'slug',
        'published',
        'language',
    ];
    /**
     * The relationships a resource can have
     *
     * @var array
     */
    protected $relationships = [
        'metadetails',
        'collections',
        'fragments',
        'pages',
        'ownedbypages'
    ];
    /**
     * The resources name
     *
     * @var array
     */
    protected $resource = 'pages';
}
