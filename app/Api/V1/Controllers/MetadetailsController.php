<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Models\Metadetail;
use App\Api\V1\Transformers\MetadetailTransformer;
use App\Api\V1\Validators\MetadetailValidator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
/**
 * Metadetails resource representation.
 *
 * @Resource("Metadetails", uri="/metadetails")
 */
class MetadetailsController extends ApiController
{

}
