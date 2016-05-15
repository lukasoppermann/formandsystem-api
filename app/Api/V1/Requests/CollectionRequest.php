<?php

namespace App\Api\V1\Requests;

use App\Api\V1\Requests\AbstractResourceRequest;

class CollectionRequest extends AbstractResourceRequest
{
    use RequestAuthorization;
    /**
     * scopes available for the endpoint
     *
     * @var [array]
     */
    public $scopes = [
        'get'       => 'content.get',
        'post'      => 'content.post',
        'delete'    => 'content.delete',
        'patch'     => 'content.patch',
    ];
    /**
     * rules for various request types
     *
     * @var [array]
     */
    protected $rules = [
        // POST
        'post' => [
            'data.type' => 'required|in:collections',
            'data.attributes.name' => 'required|string',
            'data.attributes.slug' => 'required|string|alpha_dash',
        ],
        // PATCH
        'patch' => [
            'data.id' => 'required|string',
            'data.type' => 'required|in:collections',
            'data.attributes.name' => 'string',
            'data.attributes.slug' => 'string|alpha_dash',
            'data.attributes.is_trashed' => 'boolean',
        ]
    ];
    /**
     * relationships of the endpoint
     *
     * @var [array]
     */
    public $relationships = [
        'pages',
        'ownedByPages',
        'collections',
        'ownedByCollections',
        'fragments',
        'ownedByFragments',
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
