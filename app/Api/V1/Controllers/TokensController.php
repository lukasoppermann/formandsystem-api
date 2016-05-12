<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use \App\Api\V1\Serializer\JsonApiExtendedSerializer;
// use Ramsey\Uuid\Uuid;

class TokensController extends ApiController
{
    /*
     * create oauth user
     */
    public function store(Request $request)
    {
        // create token
        $token = app('oauth2-server.authorizer')->issueAccessToken();
        
        // get model
        $model = $this->newModel()->find($token['access_token']);
        // return result
        return $this->response->item($model, $this->newTransformer(), ['key' => $this->resource])
            ->setStatusCode(201);
    }
}
