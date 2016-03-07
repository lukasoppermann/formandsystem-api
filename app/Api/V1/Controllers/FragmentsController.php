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

    public function show(Request $request, $fragment_id)
    {
        $fragment = $this->validateResourceExists(Fragment::find($fragment_id));

        return $this->response->item($fragment, new FragmentTransformer, ['key' => 'fragments']);
    }

    public function getFragments(Request $request, $fragment_id)
    {
        $fragment = $this->validateResourceExists(Fragment::find($fragment_id));

        return $this->getRelated(
            $request,
            $fragment->fragments,
            'fragments'
        );
    }

    public function getFragmentsRelationships(Request $request, $fragment_id)
    {
        $fragment = $this->validateResourceExists(Fragment::find($fragment_id));
        // return relationship
        return $this->getRelationship([
            'ids' => $fragment->fragments->lists('id'),
            'type' => 'fragments',
            'parent_id' => $fragment_id,
            'parent_type' => 'fragments'
        ]);
    }

}
