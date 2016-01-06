<?php

namespace App\Api\V1\Transformers;

use League\Fractal\TransformerAbstract;
use App\Api\V1\Models\Page;


class PageTransformer extends TransformerAbstract
{

    protected $defaultIncludes = [
        // 'fragments'
    ];
    /**
      * List of resources possible to include
      *
      * @var array
      */
    protected $availableIncludes = [
      'fragments'
    ];

    public function transform(Page $page)
    {
        return [
            'id'            => $page->id,
            'menu_label'    => $page->menu_label,
            'slug'          => $page->slug,
            'published'     => (bool)$page->published,
            'language'      => $page->language,
            'title'         => $page->title,
            'description'   => $page->description,
            'created_at'    => (string)$page->created_at,
            'updated_at'    => (string)$page->updated_at,
        ];
    }

    public function includeFragments( Page $page )
    {
        return $this->collection( $page->fragments, new FragmentTransformer, 'fragments' );
    }
}
