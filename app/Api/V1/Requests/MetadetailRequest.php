<?php

namespace App\Api\V1\Requests;

use App\Api\V1\Requests\AbstractResourceRequest;

class MetadetailRequest extends AbstractResourceRequest
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
            'data.type'              => 'required|in:metadetails',
            'data.attributes.type'   => 'required|string|alpha_dash',
            'data.attributes.value'  => 'required|string_or_array'
        ],
        // PATCH
        'patch' => [
            'data.id'                    => 'required|string',
            'data.type'                  => 'required|in:metadetails',
            'data.attributes.type'       => 'string|alpha_dash',
            'data.attributes.value'      => 'string_or_array',
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
        'ownedByFragments',
        'ownedByImages',
        'images',
    ];
    /**
     * filter available in for the endpoint
     *
     * @var [array]
     */
    public $filter = [
        'type'
    ];
}
