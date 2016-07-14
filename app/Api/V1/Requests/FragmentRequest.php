<?php

namespace App\Api\V1\Requests;

use App\Api\V1\Requests\AbstractResourceRequest;

class FragmentRequest extends AbstractResourceRequest
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
            'data.type'                     => 'required|in:fragments',
            'data.attributes.name'          => 'string',
            'data.attributes.type'          => 'required|string',
            'data.attributes.position'      => 'integer',
            'data.attributes.data'          => '',
            'data.attributes.meta'          => '',
        ],
        // PATCH
        'patch' => [
            'data.id'                       => 'required|string',
            'data.type'                     => 'required|in:fragments',
            'data.attributes.position'      => 'integer',
            'data.attributes.name'          => 'string',
            'data.attributes.type'          => 'string',
            'data.attributes.data'          => '',
            'data.attributes.is_trashed'    => 'boolean',
            'data.attributes.meta'          => '',
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
        'id',
    ];
}
