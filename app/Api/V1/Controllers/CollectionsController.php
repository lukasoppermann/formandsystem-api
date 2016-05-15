<?php

namespace App\Api\V1\Controllers;

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
     * The resources name
     *
     * @var array
     */
    protected $resource = 'collections';
}
