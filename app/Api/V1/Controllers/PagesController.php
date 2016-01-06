<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Models\Page;
use App\Api\V1\Transformers\PageTransformer;
use League\Fractal\Serializer\JsonApiSerializer;
use League\Fractal\Serializer\DataArraySerializer;
use Illuminate\Http\Request;

class PagesController extends ApiController
{
    protected $availableFilters = [
        'slug'
    ];

    public function index(Request $request)
    {
        $pages = $this->getFilteredResult(new Page, $request->input('filter'));

        return $this->response->collection($pages, new PageTransformer, ['key' => 'pages']);
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
}
