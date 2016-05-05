<?php

namespace App\Api\V1\Requests;

use App\Api\V1\Requests\AbstractResourceRequest;

class PageRequest extends AbstractResourceRequest
{
    /**
     * rules for various request types
     *
     * @var [array]
     */
    protected $rules = [
        // POST
        'post' => [
            'type'                      => 'required|in:pages',
            'attributes.menu_label'     => 'required|string',
            'attributes.slug'           => 'required|string|alpha_dash',
            'attributes.published'      => 'required|boolean',
            'attributes.language'       => 'required|string|size:2',
            'attributes.title'          => 'string',
            'attributes.description'    => 'string'
        ],
        // PATCH
        'patch' => [
            'type'                      => 'required|in:pages',
            'id'                        => 'required|string',
            'attributes.menu_label'     => 'string',
            'attributes.slug'           => 'string|alpha_dash',
            'attributes.published'      => 'boolean',
            'attributes.language'       => 'string|size:2',
            'attributes.title'          => 'string',
            'attributes.description'    => 'string',
            'attributes.is_trashed'     => 'boolean',
        ]
    ];
    /**
     * relationships of the endpoint
     *
     * @var [array]
     */
    public $relationships = [
        'pages',
        'collections',
        'fragments',
        'metadetails'
    ];
    /**
     * filter available in for the endpoint
     *
     * @var [array]
     */
    public $filter = [
        'slug'
    ];
}
