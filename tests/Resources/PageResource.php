<?php

namespace Lukasoppermann\Testing\Resources;

use Lukasoppermann\Testing\Resources\ApiResource;

class PageResource extends ApiResource{
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
            'metadetails',
            'collections',
            'fragments',
            // 'pages'
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
            'type' => 'in:pages',
            'attributes' => [
                'menu_label' => 'string',
                'slug' => 'string',
                'published' => 'bool',
                'language' => 'in:de,en',
                'title' => 'string',
                'description' => 'string'
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
            "type" => "pages",
            'attributes' => [
                'menu_label' => 'menu label',
                'slug' => 'page-slug',
                'published' => '1',
                'language' => 'en',
                'title' => 'Title of the page',
                'description' => 'Description of the page'
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
            "type" => "pages",
            'attributes' => [
                'slug' => 'new-page-slug',
                'published' => '0',
                'language' => 'de',
            ]
        ];
    }

}
