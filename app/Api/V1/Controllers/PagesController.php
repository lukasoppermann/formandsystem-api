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
        $page = $this->validateResourceExists(Page::find($page_id));

        return $this->response->item($page, new PageTransformer, ['key' => 'pages']);
    }

    public function getCollections(Request $request, $page_id){
        $page = $this->validateResourceExists(Page::find($page_id));

        return $this->getRelated(
            $request,
            $page->collections(),
            'collections'
        );

    }
    /*
     * get relationship
     */
    public function getCollectionsRelationships(Request $request, $page_id){
        $page = $this->validateResourceExists(Page::find($page_id));
        // return relationship
        return $this->getRelationship([
            'ids' => $page->collections->lists('id'),
            'type' => 'collections',
            'parent_id' => $page_id,
            'parent_type' => 'pages'
        ]);

    }
    /*
     * get related
     */
    public function getFragments(Request $request, $page_id){
        $page = $this->validateResourceExists(Page::find($page_id));
        // return related objects
        return $this->getRelated($request,
            $page->fragments(),
            'fragments'
        );

    }
    /*
     * get relationship
     */
    public function getFragmentsRelationships(Request $request, $page_id){
        $page = $this->validateResourceExists(Page::find($page_id));
        // return relationship
        return $this->getRelationship([
            'ids' => $page->fragments->lists('id'),
            'type' => 'fragments',
            'parent_id' => $page_id,
            'parent_type' => 'pages'
        ]);

    }

    public function getMetadetails(Request $request, $page_id){
        $page = $this->validateResourceExists(Page::find($page_id));
        // return related objects
        return $this->getRelated($request,
            $page->metadetails(),
            'metadetails'
        );

    }

    public function getMetadetailsRelationships(Request $request, $page_id){
        $page = $this->validateResourceExists(Page::find($page_id));
        // return relationship
        return $this->getRelationship([
            'ids' => $page->metadetails->lists('id'),
            'type' => 'metadetails',
            'parent_id' => $page_id,
            'parent_type' => 'pages'
        ]);

    }
}
