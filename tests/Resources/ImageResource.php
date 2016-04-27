<?php

namespace Lukasoppermann\Testing\Resources;

use Lukasoppermann\Testing\Resources\ApiResource;

class ImageResource extends ApiResource{
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
            'fragments',
            'images'
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
            'type' => 'in:images',
            'attributes' => [
                'link' => 'string',
                'slug' => 'string',
                'bytesize' => 'int',
                'width' => 'int',
                'height' => 'int',
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
    public function data($extension = 'png'){
        $slug = $this->faker->slug();
        return [
            "type" => "images",
            "attributes" => [
                'link' => $slug.'.'.$extension,
                'slug' => $slug,
                'bytesize' => 122345,
                'width' => 300,
                'height' => 500,
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
            "type" => "images",
            "attributes" => [
                'slug' => 'new-image-slug',
            ]
        ];
    }

}
