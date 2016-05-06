<?php

namespace Lukasoppermann\Testing\Resources;

use Lukasoppermann\Testing\Resources\ApiResource;

class FragmentResource extends ApiResource{
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
     * @method filter
     *
     * @return array
     */
    public function relationships(){
        return [
            'fragments',
            'ownedByFragments',
            'collections',
            'ownedByCollections',
            'ownedByPages',
            'images',
            'metadetails',
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
            'type' => 'in:fragments',
            'attributes' => [
                'name' => 'string',
                'type' => 'required|string',
                'data' => '',
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
            "type" => "fragments",
            'attributes' => [
                'name' => 'new fragment image',
                'type' => 'image',
                'data' => json_encode([
                    'title' => 'image title',
                    'description' => 'image description'
                ]),
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
            "type" => "fragments",
            'attributes' => [
                'name' => 'new fragment name',
                'data' => json_encode([
                    'title' => 'title of image'
                ]),
            ]
        ];
    }

}
