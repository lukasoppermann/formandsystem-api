<?php

namespace App\Api\V1\Requests\Metadetails;

use App\Api\V1\Requests\ResourceRequest;

class MetadetailRequest extends ResourceRequest
{
    /**
     * rules for various request types
     *
     * @var [array]
     */
    protected $rules = [
        // POST
        'post' => [
            'type'              => 'required|in:metadetails',
            'attributes.type'   => 'required|string|alpha_dash',
            'attributes.value'  => 'required|string_or_array'
        ],
        // PATCH
        'patch' => [
            'id'                    => 'required|string',
            'type'                  => 'required|in:metadetails',
            'attributes.type'       => 'string|alpha_dash',
            'attributes.value'      => 'string_or_array',
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
