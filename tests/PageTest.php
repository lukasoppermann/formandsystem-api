<?php

class PageTest extends TestCase
{
    /**
     * @test
     */
    public function get_pages()
    {
        $response = $this->client->get('/pages/', [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);
        // check for HTTP_OK
        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());

        // Check if data is correctly formatted & everything is returned
        $received = $this->getResponseArray($response)['data'][0];
        $expected = [
            'id' => 'string',
            'type' => 'in:pages',
            'attributes' => [
                'menu_label' => 'string',
                'slug' => 'string',
                'published' => 'integer|in:0,1',
                'language' => 'in:de,en',
                'title' => 'string',
                'description' => 'string'
            ],
            'links' => [
                'self' => 'in:'.$_ENV['API_DOMAIN'].'/'.$received['type'].'/'.$received['id']
            ]
        ];
        $this->assertValidArray($expected, $received);
    }

    /**
     * @test
     */
    public function get_pages_with_pagination_page_one()
    {
        // check for default count
        $defaultCount = 20;
        $response = $this->client->get('/pages/', [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);
        $received = $this->getResponseArray($response)['data'];

        $this->assertEquals($defaultCount, count($received), $defaultCount.' results should be returned.');
    }
    /**
     * @test
     */
    public function get_a_page_by_id()
    {
        $pageId = (new App\Api\V1\Models\Page)->first()->id;

        $response = $this->client->get('/pages/'.$pageId, [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);
        // check for HTTP_OK
        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());

        // Check if data is correctly formatted & everything is returned
        $received = $this->getResponseArray($response)['data'];
        $expected = [
            'id' => 'string',
            'type' => 'in:pages',
            'attributes' => [
                'menu_label' => 'string',
                'slug' => 'string',
                'published' => 'integer|in:0,1',
                'language' => 'in:de,en',
                'title' => 'string',
                'description' => 'string'
            ],
            'links' => [
                'self' => 'in:'.$_ENV['API_DOMAIN'].'/'.$received['type'].'/'.$received['id']
            ]
        ];
        $this->assertValidArray($expected, $received);
    }

    /**
     * @test
     */
    public function get_a_page_by_wrong_id()
    {

        $response = $this->client->get('/pages/1', [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);
        // check for HTTP_NOT_FOUND
        $this->assertEquals(self::HTTP_NOT_FOUND, $response->getStatusCode());
        // Check if error is correctly formatted & everything is returned
        $received = $this->getResponseArray($response);
        $expected = [
            'error' => [
                'message' => 'string',
                'status_code' => 'integer|in:'.self::HTTP_NOT_FOUND,
            ]
        ];
        $this->assertValidArray($expected, $received);
    }

    /**
     * @test
     */
    public function get_pages_collections()
    {
        $id = App\Api\V1\Models\Page::first()->id;

        $response = $this->client->get('/pages/'.$id.'/collections', [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);
        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());

        $received = $this->getResponseArray($response);
        $expected = [
            'data' => [
                0 => [
                    'type' => 'in:collections',
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
    public function get_collection_from_page_wrong_id()
    {
        $response = $this->client->get('/pages/1/collections', [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        $this->assertEquals(self::HTTP_NOT_FOUND, $response->getStatusCode());

        // Check if error is correctly formatted & everything is returned
        $received = $this->getResponseArray($response);
        $expected = [
            'error' => [
                'message' => 'string',
                'status_code' => 'integer|in:'.self::HTTP_NOT_FOUND,
            ]
        ];
        $this->assertValidArray($expected, $received);
    }

}
