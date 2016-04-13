<?php

namespace Lukasoppermann\Testing\Resources;

class Page{

    public function expected(){
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

}
