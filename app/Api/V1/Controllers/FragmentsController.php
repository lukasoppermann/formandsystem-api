<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Models\Fragment;
use App\Api\V1\Transformers\FragmentTransformer;
use League\Fractal\Serializer\DataArraySerializer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class FragmentsController extends ApiController
{
    /**
     * The relationships a resource can have
     *
     * @var array
     */
    protected $relationships = [
        'pages',
        'images',
        'fragments',
    ];
    /**
     * The filters that are allowed in requests
     *
     * @var array
     */
    protected $availableFilters = [
        'type',
        'name'
    ];
    /**
     * The resources name
     *
     * @var array
     */
    protected $resource = 'fragments';

}
