<?php

class PageTest extends TestCase
{
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
                'self' => 'in:'.$_ENV['API_DOMAIN'].'/'.$received[0]['type'].'/'.$received[0]['id']
            ]
        ];
        $this->assertValidArray($expected, $received[0]);
    }

    /**
     * @test
     */
    public function get_pages_with_pagination()
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
        // check for default count
        // $limit = 10;
        // $offset = 5
        // $response = $this->client->get('/pages/', [
        //     'headers' => [
        //         'Accept' => 'application/json',
        //     ],
        // ]);
        // $this->assertEquals($defaultCount, count($received), $defaultCount.' results should be returned.');
    }



}
