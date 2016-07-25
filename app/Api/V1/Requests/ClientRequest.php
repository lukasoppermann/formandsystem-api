<?php

namespace App\Api\V1\Requests;

use App\Api\V1\Requests\AbstractResourceRequest;

class ClientRequest extends AbstractResourceRequest
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
        'get'       => 'client.get',
        'delete'    => 'client.delete',
        'post'      => 'client.post',
    ];
    /**
     * rules for various request types
     *
     * @var [array]
     */
    protected $rules = [
        // POST
        'post' => [
            'data.type'                 => 'required|in:clients',
            'data.attributes.name'      => 'required|string',
            'data.attributes.scopes'    => 'required|string',
        ]
    ];
    /**
     * relationships of the endpoint
     *
     * @var [array]
     */
    public $relationships = [
        'details',
    ];
}
