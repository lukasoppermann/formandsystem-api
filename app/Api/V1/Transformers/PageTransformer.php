<?php

namespace App\Api\V1\Transformers;

use League\Fractal\TransformerAbstract;
use App\Api\V1\Models\Page;

class PageTransformer extends TransformerAbstract
{

    public function transform(Page $page)
    {
        return $page;
    }
}
