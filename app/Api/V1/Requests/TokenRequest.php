<?php

namespace App\Api\V1\Requests;

use App\Api\V1\Requests\AbstractResourceRequest;

class TokenRequest extends AbstractResourceRequest
{
    /**
     * rules for various request types
     *
     * @var [array]
     */
    protected $rules = [
        // POST
        'post' => [
            // needs to remove auto-adding data before this works
            // 'grant_type'    => 'required|in:client_credentials',
            // 'client_id'     => 'required|string',
            // 'client_secret' => 'required|string',
            // 'scope'         => 'required|string',
        ],
    ];
}
