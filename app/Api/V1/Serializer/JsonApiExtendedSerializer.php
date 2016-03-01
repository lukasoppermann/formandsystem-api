<?php

namespace App\Api\V1\Serializer;

use InvalidArgumentException;
use League\Fractal\Resource\ResourceInterface;

class JsonApiExtendedSerializer extends \League\Fractal\Serializer\JsonApiSerializer
{
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
            $resource['data']['links'] = [
                'self' => "{$this->baseUrl}/$resourceKey/$id",
            ];

        }

        return $resource;
    }

    /**
     * Serialize the included data.
     *
     * @param ResourceInterface $resource
     * @param array             $data
     *
     * @return array
     */
    public function includedData(ResourceInterface $resource, array $data)
    {
        list($serializedData, $linkedIds) = $this->pullOutNestedIncludedData(
            $resource,
            $data
        );

        foreach ($data as $value) {
            foreach ($value as $includeKey => $includeObject) {
                if ($this->isNull($includeObject) || $this->isEmpty($includeObject)) {
                    continue;
                }
                if ($this->isCollection($includeObject)) {
                    $includeObjects = $includeObject['data'];
                }
                else {
                    $includeObjects = [$includeObject['data']];
                }

                foreach ($includeObjects as $object) {
                    $includeType = $object['type'];
                    $includeId = $object['id'];
                    $cacheKey = "$includeType:$includeId";
                    if (!array_key_exists($cacheKey, $linkedIds)) {
                        $serializedData[] = $object;
                        $linkedIds[$cacheKey] = $object;
                    }
                }
            }
        }

        return empty($serializedData) ? [] : ['included' => $serializedData];
    }

    public function injectData($data, $includedData)
    {

        $relationships = $this->parseRelationships($includedData);

        if (!empty($relationships)) {
            $data = $this->fillRelationships($data, $relationships);
        }

        return $data;
    }
}
