<?php

namespace Lukasoppermann\Testing\Resources;

class Collection{

    /**
     * returns expected blueprint
     *
     * @method expected
     *
     * @return array
     */
    public function expected(){
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
     * @method post
     *
     * @return array
     */
    public function post(){
        return [
            "type" => "collections",
            "attributes" => [
                "name" => "collection name",
                "slug" => "collection-slug"
            ]
        ];
    }
    /**
     * returns dummy post data
     *
     * @method post
     *
     * @return array
     */
    public function post_incomplete(){
        return [
            "type" => "collections",
            "attributes" => [
                "name" => "patched collection name"
            ]
        ];
    }

}
