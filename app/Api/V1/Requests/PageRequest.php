<?php

namespace App\Api\V1\Requests;

use App\Api\V1\Requests\AbstractResourceRequest;

class PageRequest extends AbstractResourceRequest
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
            'data.type'                      => 'required|in:pages',
            'data.attributes.menu_label'     => 'required|string',
            'data.attributes.position'       => 'integer',
            'data.attributes.slug'           => 'required|string|alpha_dash',
            'data.attributes.published'      => 'required|boolean',
            'data.attributes.language'       => 'required|string|size:2',
            'data.attributes.title'          => 'string',
            'data.attributes.description'    => 'string'
        ],
        // PATCH
        'patch' => [
            'data.type'                      => 'required|in:pages',
            'data.id'                        => 'required|string',
            'data.attributes.menu_label'     => 'string',
            'data.attributes.position'       => 'integer',
            'data.attributes.slug'           => 'string|alpha_dash',
            'data.attributes.published'      => 'boolean',
            'data.attributes.language'       => 'string|size:2',
            'data.attributes.title'          => 'string',
            'data.attributes.description'    => 'string',
            'data.attributes.is_trashed'     => 'boolean',
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
        'metadetails',
    ];
    /**
     * filter available in for the endpoint
     *
     * @var [array]
     */
    public $filter = [
        'slug',
        'published',
        'language',
        'id',
    ];
}
