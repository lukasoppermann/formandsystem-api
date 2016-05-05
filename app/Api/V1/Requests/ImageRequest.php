<?php

namespace App\Api\V1\Requests;

use App\Api\V1\Requests\AbstractResourceRequest;

class ImageRequest extends AbstractResourceRequest
{
    /**
     * rules for various request types
     *
     * @var [array]
     */
    protected $rules = [
        // POST
        'post' => [
            'data.type'                  => 'required|in:images',
            'data.attributes.slug'       => 'string|required',
            'data.attributes.link'       => 'string|required_without:data.attributes.filename',
            'data.attributes.filename'   => 'string|required_without:data.attributes.link',
            'data.attributes.bytesize'   => 'int|required_with:data.attributes.filename',
            'data.attributes.width'      => 'int|required_with:data.attributes.filename',
            'data.attributes.height'     => 'int|required_with:data.attributes.filename',
        ],
        // PATCH
        'patch' => [
            'data.id' => 'required|string',
            'data.type' => 'required|in:images',
            'data.attributes.slug' => 'string',
            'data.attributes.is_trashed' => 'boolean',
        ]
    ];
    /**
     * relationships of the endpoint
     *
     * @var [array]
     */
    public $relationships = [
        'fragments',
        'images',
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
