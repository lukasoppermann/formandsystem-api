<?php

namespace Lukasoppermann\Testing\Resources;

use Lukasoppermann\Testing\Resources\ApiResource;

class CollectionResource extends ApiResource{
    /**
     * returns available filters
     *
     * @method filter
     *
     * @return array
     */
    public function filter(){
        return [
            'slug'
        ];
    }
    /**
     * returns available filters
     *
     * @method filter
     *
     * @return array
     */
    public function relationships(){
        return [
            'pages',
            'ownedByPages',
            'collections',
            'ownedByCollections',
            'fragments',
            'ownedByFragments',
        ];
    }
    /**
     * returns expected blueprint
     *
     * @method blueprint
     *
     * @return array
     */
    public function blueprint(){
        return [
            'id' => 'string',
            'type' => 'in:collections',
            'attributes' => [
                'name' => 'string',
                'slug' => 'string',
            ]
        ];
    }
    /**
     * returns dummy post data
     *
     * @method data
     *
     * @return array
     */
    public function data(){
        return [
            "type" => "collections",
            "attributes" => [
                "name" => "collection name",
                "slug" => "collection-slug"
            ]
        ];
    }
    /**
     * returns incomplete dummy post data used for patch
     *
     * @method incomplete
     *
     * @return array
     */
    public function incomplete(){
        return [
            "type" => "collections",
            "attributes" => [
                "name" => "patched collection name"
            ]
        ];
    }

}
