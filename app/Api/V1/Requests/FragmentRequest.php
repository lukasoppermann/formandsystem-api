<?php

namespace App\Api\V1\Requests;

use App\Api\V1\Requests\AbstractResourceRequest;

class FragmentRequest extends AbstractResourceRequest
{
    use RequestAuthorization;
    /**
     * rules for various request types
     *
     * @var [array]
     */
    protected $rules = [
        // POST
        'post' => [
            'data.type' => 'required|in:fragments',
            'data.attributes.name' => 'string',
            'data.attributes.type' => 'required|string',
            'data.attributes.data' => '',
        ],
        // PATCH
        'patch' => [
            'data.id' => 'required|string',
            'data.type' => 'required|in:fragments',
            'data.attributes.name' => 'string',
            'data.attributes.type' => 'string',
            'data.attributes.data' => '',
            'data.attributes.is_trashed' => 'boolean',
        ]
    ];
    /**
     * relationships of the endpoint
     *
     * @var [array]
     */
    public $relationships = [
        'ownedByPages',
        'fragments',
        'ownedByFragments',
        'collections',
        'ownedByCollections',
        'images',
        'metadetails',
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
