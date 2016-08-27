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
            'data.attributes.position' => 'integer',
            'data.attributes.name' => 'required|string',
            'data.attributes.slug' => 'required|alpha_dash',
            'data.attributes.type' => 'required|alpha_dash',
            'data.attributes.key' => 'alpha_dash',
        ],
        // PATCH
        'patch' => [
            'data.id'                       => 'required|string',
            'data.type'                     => 'required|in:collections',
            'data.attributes.position'      => 'integer',
            'data.attributes.name'          => 'string',
            'data.attributes.slug'          => 'alpha_dash',
            'data.attributes.type'          => 'alpha_dash',
            'data.attributes.is_trashed'    => 'boolean',
            'data.attributes.key'           => 'alpha_dash',
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
        'id',
        'type',
        'name',
        'key'
    ];
}
