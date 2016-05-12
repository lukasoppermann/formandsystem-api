<?php

namespace App\Api\V1\Requests;

use App\Api\V1\Requests\AbstractResourceRequest;

class DetailRequest extends AbstractResourceRequest
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
            'data.type' => 'required|in:details',
            'data.attributes.type' => 'required|string|in:database,image_ftp,backup_ftp',
            'data.attributes.data' => 'required|string_or_array',
        ]
    ];
    /**
     * relationships of the endpoint
     *
     * @var [array]
     */
    public $relationships = [
        'ownedByClients',
    ];
}
