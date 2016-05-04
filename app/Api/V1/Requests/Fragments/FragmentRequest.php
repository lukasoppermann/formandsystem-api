<?php

namespace App\Api\V1\Requests\Fragments;

use App\Api\V1\Requests\ResourceRequest;

class FragmentRequest extends ResourceRequest
{
    /**
     * rules for various request types
     *
     * @var [array]
     */
    protected $rules = [
        // POST
        'post' => [
            'type' => 'required|in:fragments',
            'attributes.name' => 'string',
            'attributes.type' => 'required|string',
            'attributes.data' => '',
        ],
        // PATCH
        'patch' => [
            'id' => 'required|string',
            'type' => 'required|in:fragments',
            'attributes.name' => 'string',
            'attributes.type' => 'string',
            'attributes.data' => '',
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
        'fragments',
        'images'
    ];
    /**
     * filter available in for the endpoint
     *
     * @var [array]
     */
    public $filter = [
        'type',
        'name',
    ];
}
