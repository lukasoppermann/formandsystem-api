<?php

namespace App\Api\V1\Serializer;

use InvalidArgumentException;
use League\Fractal\Resource\ResourceInterface;

class JsonApiExtendedSerializer extends \League\Fractal\Serializer\JsonApiSerializer
{
    protected $availableIncludes = [];
    /**
     * Serialize an item.
     *
     * @param string $resourceKey
     * @param array  $data
     *
     * @return array
     */
    public function item($resourceKey, array $data)
    {
        $id = $this->getIdFromData($data);

        $resource = [
            'data' => [
                'type' => $resourceKey,
                'id' => "$id",
                'attributes' => $data,
            ],
        ];

        unset($resource['data']['attributes']['id']);

        if ($this->shouldIncludeLinks()) {
            // add self link
            $resource['data']['links'] = [
                'self' => "{$this->baseUrl}/$resourceKey/$id",
            ];
            // add relationship links
            if(isset($resource['data']['attributes']['relationships'])){
                $resource['data']['relationships'] = $resource['data']['attributes']['relationships'];
            }
            // add more links
            if(isset($resource['data']['attributes']['links'])){
                foreach($resource['data']['attributes']['links'] as $key => $link){
                    $resource['data']['links'][$key] = $link;
                }
            }

        }

        unset($resource['data']['attributes']['relationships']);

        return $resource;
    }
}
