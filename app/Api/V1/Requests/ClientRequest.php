<?php

namespace App\Api\V1\Requests;

use App\Api\V1\Requests\AbstractResourceRequest;

class ClientRequest extends AbstractResourceRequest
{
    /**
     * rules for various request types
     *
     * @var [array]
     */
    protected $rules = [
        // POST
        'post' => [
            'data.type' => 'required|in:authentications',
            'data.attributes.name' => 'required|string',
        ],
        // PATCH
        'patch' => [

        ]
    ];
}
