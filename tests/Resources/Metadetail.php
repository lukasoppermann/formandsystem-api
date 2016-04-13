<?php

namespace Lukasoppermann\Testing\Resources;

class Metadetail{

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
            'type' => 'in:metadetails',
            'attributes' => [
                'key' => 'string',
                'value' => 'stringOrArray',
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
            "type" => "metadetails",
            "attributes" => [
                "key" => "language",
                "value" => [
                    'lang' => 'de',
                    'name' => 'deutsch'
                ]
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
            "type" => "metadetails",
            "attributes" => [
                "value" => "patched value"
            ]
        ];
    }

}
