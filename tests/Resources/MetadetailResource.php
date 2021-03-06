<?php

namespace Lukasoppermann\Testing\Resources;

use Lukasoppermann\Testing\Resources\ApiResource;

class MetadetailResource extends ApiResource{
    /**
     * returns available filters
     *
     * @method filter
     *
     * @return array
     */
    public function filter(){
        return [
            'type'
        ];
    }
    /**
     * returns available filters
     *
     * @method relationships
     *
     * @return array
     */
    public function relationships(){
        return [
            'ownedByPages',
            'ownedByFragments',
            'ownedByImages',
            'images',
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
            'type' => 'in:metadetails',
            'attributes' => [
                'type' => 'string',
                'data' => 'stringOrArray',
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
            "type" => "metadetails",
            "attributes" => [
                "type" => "language",
                "data" => [
                    'lang' => 'de',
                    'name' => 'deutsch'
                ]
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
            "type" => "metadetails",
            "attributes" => [
                "data" => "patched value"
            ]
        ];
    }

}
