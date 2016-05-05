<?php

namespace App\Api\V1\Requests;

use App\Api\V1\Requests\AbstractResourceRequest;

class CollectionRequest extends AbstractResourceRequest
{
    /**
     * rules for various request types
     *
     * @var [array]
     */
    protected $rules = [
        // POST
        'post' => [
            'type' => 'required|in:collections',
            'attributes.name' => 'required|string',
            'attributes.slug' => 'required|string|alpha_dash',
        ],
        // PATCH
        'patch' => [
            'id' => 'required|string',
            'type' => 'required|in:collections',
            'attributes.name' => 'string',
            'attributes.slug' => 'string|alpha_dash',
            'attributes.is_trashed' => 'boolean',
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
        'ownedByCollections',
    ];
    /**
     * filter available in for the endpoint
     *
     * @var [array]
     */
    public $filter = [
        'slug',
    ];
}
