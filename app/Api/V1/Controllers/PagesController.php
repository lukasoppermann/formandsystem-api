<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Models\Page;
use App\Api\V1\Transformers\PageTransformer;
use App\Api\V1\Transformers\CollectionTransformer;
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
        // get the pages for the specific collection
        $thisPage = Page::find($page_id)->collections;
        //
        $page = $request->input('page', 1); // Get the current page or default to 1
        $offset = ($page * $this->perPage) - $this->perPage;

        return $this->response->paginator(new LengthAwarePaginator($thisPage->slice($offset, $this->perPage), $thisPage->count(), $this->perPage, $page, ['path' => $request->url(), 'query' => $request->query()]), new CollectionTransformer, ['key' => 'collections']);
    }

    public function getRelationshipsCollections(Request $request, $page_id){
        // no fractal implementation yet
        foreach(Page::find($page_id)->collections->lists('id') as $id){
            $collections[] = [
                'id' => $id,
                'type' => 'collections'
            ];
        }

        return $this->response->array([
            'links' => [
                'self' => $_ENV['API_DOMAIN'].'/pages/'.$page_id.'/relationships/collections',
                'related' => $_ENV['API_DOMAIN'].'/pages/'.$page_id.'/collections'
            ],
            'data' => $collections
        ]);

    }
}
