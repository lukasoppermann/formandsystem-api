<?php

namespace App\Api\V1\Controllers;

use Illuminate\Http\Request;
use \App\Api\V1\Serializer\JsonApiExtendedSerializer;
use Ramsey\Uuid\Uuid;

class ClientsController extends ApiController
{
    /**
     * The resources name
     *
     * @var array
     */
    protected $resource = 'clients';
    /*
     * create oauth user
     */
    public function store(Request $request)
    {
        $newClient = [
            'id' => bin2hex(random_bytes(30)),
            'secret' => bin2hex(random_bytes(30)),
            'name' => $request->json('data.attributes.name'),
        ];
        // create item
        $model = $this->newModel()->create($newClient);
        // return result
        return $this->response->item($model, $this->newTransformer(), ['key' => $this->resource])
            ->setStatusCode(201)
            ->withHeader('Location', $_ENV['API_DOMAIN'].'/'.$this->resource.'/'.$model->id);
    }
}
