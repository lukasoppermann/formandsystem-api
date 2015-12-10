<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Models\Page;
use App\Api\V1\Transformers\PageTransformer;

class PagesController extends ApiController
{
    public function show($page_id)
    {
        // $page = Page::where('id', $page_id)->take(20)->get();
        $page = Page::with('fragments')->get();
        return $this->response->collection($page, new PageTransformer);
    }
}
