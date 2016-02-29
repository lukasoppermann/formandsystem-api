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
    protected $availableFilters = [
        'slug'
    ];

    public function index(Request $request)
    {
        $pages = $this->getFilteredResult(new Page, $request->input('filter'));

        return $this->response->paginator($pages, new PageTransformer, ['key' => 'pages']);
    }

    public function show($page_id)
    {
            $page = Page::find($page_id);

            // no entry exists, throw exception, will be converted to jsonapi response
            if ($page === null) {
                throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
            }

            return $this->response->item($page, new PageTransformer, ['key' => 'pages']);
    }

    public function getCollections(Request $request, $page_id){

        // no entry exists, throw exception, will be converted to jsonapi response
        if (Page::find($page_id) === null) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
        }

        return $this->getRelated(
            $request,
            Page::find($page_id)->collections,
            'collections'
        );

    }

    public function getRelationshipsCollections(Request $request, $page_id){
        // retrieve related ids
        $ids = Page::find($page_id)->collections->lists('id');

        return $this->getRelationship([
            'ids' => $ids,
            'type' => 'collections',
            'parent_id' => $page_id,
            'parent_type' => 'pages'
        ]);

    }

    public function getFragments(Request $request, $page_id){

        return $this->getRelated($request,
            Page::find($page_id)->fragments,
            'fragments'
        );

    }

    public function getRelationshipsFragments(Request $request, $page_id){

        $model = Page::find($page_id);

        return $this->getRelationship($page_id, 'pages', 'fragments', $model);

    }
}
