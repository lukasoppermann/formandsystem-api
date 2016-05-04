<?php

namespace App\Api\V1\Requests\Images;

use App\Api\V1\Requests\ResourceRequest;

class ImageRequest extends ResourceRequest
{
    /**
     * rules for various request types
     *
     * @var [array]
     */
    protected $rules = [
        // POST
        'post' => [
            'type'                  => 'required|in:images',
            'attributes.slug'       => 'string|required',
            'attributes.link'       => 'string|required_without:data.attributes.filename',
            'attributes.filename'   => 'string|required_without:data.attributes.link',
            'attributes.bytesize'   => 'int|required_with:data.attributes.filename',
            'attributes.width'      => 'int|required_with:data.attributes.filename',
            'attributes.height'     => 'int|required_with:data.attributes.filename',
        ],
        // PATCH
        'patch' => [
            'id' => 'required|string',
            'type' => 'required|in:images',
            'attributes.slug' => 'string',
            'attributes.is_trashed' => 'boolean',
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
