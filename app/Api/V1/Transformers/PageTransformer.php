<?php

namespace App\Api\V1\Transformers;

use League\Fractal\TransformerAbstract;
use App\Api\V1\Models\Page;


class PageTransformer extends ApiTransformer
{

    protected $defaultIncludes = [
        'pages',
        'collections',
        'fragments',
        'metadetails',
    ];
    /**
      * List of resources possible to include
      *
      * @var array
      */
    protected $availableIncludes = [
        'pages',
        'ownedByPages',
        'collections',
        'ownedByCollections',
        'fragments',
        'metadetails',
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
            'is_trashed'    => $this->isTrashed($page),
            'relationships' => $this->relationshipsLinks('pages/'.$page->id),
        ];
    }

    public function includeFragments( Page $page )
    {
        return $this->collection( $page->fragments, new FragmentTransformer, 'fragments' );
    }

    public function includeCollections( Page $page )
    {
        return $this->collection( $page->collections, new CollectionTransformer, 'collections' );
    }

    public function includeOwnedByCollections( Page $page )
    {
        return $this->collection( $page->ownedByCollections, new CollectionTransformer, 'collections' );
    }

    public function includeMetadetails( Page $page )
    {
        return $this->collection( $page->metadetails, new MetadetailTransformer, 'metadetails' );
    }

    public function includePages( Page $page )
    {
        return $this->collection( $page->pages, new PageTransformer, 'pages' );
    }

    public function includeOwnedByPages( Page $page )
    {
        return $this->collection( $page->ownedByPages, new PageTransformer, 'pages' );
    }
}
