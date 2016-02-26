<?php

class CollectionTest extends TestCase
{
    /**
     * @test
     */
    public function get_collections()
    {
        $response = $this->getClientResponse('/collections');
        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());

        $received = $this->getResponseArray($response)['data'][0];
        $expected = [
            'id' => 'string',
            'type' => 'in:collections',
            'attributes' => [
                'name' => 'string',
                'slug' => 'string',
            ]
        ];

        $this->assertValidArray($expected, $received);
    }
    /**
     * @test
     */
    public function get_a_collection_by_id()
    {
        $id = App\Api\V1\Models\Collection::first()->id;
        $response = $this->getClientResponse('/collections/'.$id);

        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());

        $received = $this->getResponseArray($response)['data'];
        $expected = [
            'id' => 'in:'.$id,
            'type' => 'in:collections',
            'attributes' => [
                'name' => 'string',
                'slug' => 'string',
            ]
        ];

        $this->assertValidArray($expected, $received);

    }
    /**
     * @test
     */
    public function get_a_collection_by_type()
    {
        $slug = App\Api\V1\Models\Collection::first()->slug;
        $response = $this->getClientResponse('/collections?filter=[slug='.$slug.']');

        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());

        $received = $this->getResponseArray($response)['data'][0];
        $expected = [
            'id' => 'string',
            'type' => 'in:collections',
            'attributes' => [
                'name' => 'string',
                'slug' => 'in:'.$slug,
            ]
        ];

        $this->assertValidArray($expected, $received);
    }
    /**
     * @test
     */
    public function get_collection_by_wrong_id()
    {
        $response = $this->getClientResponse('collections/1');
        // check status code & response body
        $this->checkErrorResponse($response, 'HTTP_NOT_FOUND');
    }
    /**
     * @test
     */
    public function get_collections_is_paginated()
    {
        $response = $this->getClientResponse('/collections');

        $received = $this->getResponseArray($response)['meta'];
        $expected = [
            'pagination' => [
                'total' => 'int',
                'count' => 'int',
                "per_page" => 'int',
                "current_page" => 'in:1',
                "total_pages" => 'int',
            ]
        ];

        $this->assertValidArray($expected, $received);
    }
    /**
     * @test
     */
    public function get_collections_pages()
    {
        $id = App\Api\V1\Models\Collection::first()->id;
        $response = $this->getClientResponse('/collections/'.$id.'/pages');

        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());

        $received = $this->getResponseArray($response);
        $expected = [
            'data' => [
                0 => [
                    'type' => 'in:pages',
                    'id' => 'string',
                    'attributes' => 'required'
                ]
            ],
            'meta'  => [
                'pagination' => [
                    'total' => 'int',
                    'count' => 'int',
                    "per_page" => 'int',
                    "current_page" => 'in:1',
                    "total_pages" => 'int',
                ]
            ]
        ];

        $this->assertValidArray($expected, $received);
    }
    /**
     * @test
     */
    public function get_collections_relationship_pages()
    {
        $id = App\Api\V1\Models\Collection::first()->id;
        $response = $this->getClientResponse('/collections/'.$id.'/relationships/pages');

        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());

        $received = $this->getResponseArray($response);
        $expected = [
            'data' => [
                0 => [
                    'type' => 'in:pages',
                    'id' => 'string'
                ]
            ]
        ];

        $this->assertValidArray($expected, $received);
    }

}
