<?php

namespace App\Api\V1\Requests;
use Illuminate\Http\Request;
use App\Api\V1\Requests\AbstractResourceRequest;

class ImageRequest extends AbstractResourceRequest
{
    use RequestAuthorization;
    /**
     * determins if the user needs to have an image ftp access
     *
     * @var bool
     */
    protected $needs_ftp_image = TRUE;
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
        'images',
        'ownedByImages',
        'ownedByCollections',
        'ownedByFragments',
        'metadetails',
        'ownedByMetadetails',
    ];
    /**
     * filter available in for the endpoint
     *
     * @var [array]
     */
    public $filter = [
        'slug',
        'id',
    ];
}
