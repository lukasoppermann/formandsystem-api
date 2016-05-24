<?php

namespace App\Api\V1\Requests;

use App\Api\V1\Requests\AbstractResourceRequest;

class DetailRequest extends AbstractResourceRequest
{
    use RequestAuthorization;
    /**
     * defines if client data should be set in authorization method
     *
     * @var boolean
     */
    protected $setClientData = false;
    /**
     * scopes available for the endpoint
     *
     * @var [array]
     */
    public $scopes = [
        'post'      => 'client.post',
        'delete'    => 'client.delete',
    ];
    /**
     * rules for various request types
     *
     * @var [array]
     */
    protected $rules = [
        // POST
        'post' => [
            'data.type' => 'required|in:details',
            'data.attributes.type' => 'required|string|in:database,ftp_image,ftp_backup',
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
