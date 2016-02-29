<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Models\Fragment;
use App\Api\V1\Transformers\FragmentTransformer;
use League\Fractal\Serializer\DataArraySerializer;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class FragmentsController extends ApiController
{
    protected $availableFilters = [
    ];

    public function index(Request $request)
    {
        $fragments = $this->getFilteredResult(new Fragment, $request->input('filter'));

        return $this->response->paginator($fragments, new FragmentTransformer, ['key' => 'fragments']);
    }

    public function show($fragment_id)
    {
            $fragment = Fragment::find($fragment_id);

            // no entry exists, throw exception, will be converted to jsonapi response
            if ($fragment === null) {
                throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
            }

            return $this->response->item($fragment, new FragmentTransformer, ['key' => 'fragments']);
    }

}
