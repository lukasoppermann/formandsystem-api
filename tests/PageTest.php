<?php

class PageTest extends TestCase
{
    /**
     * @test
     */
    public function get_pages()
    {
        $response = $this->getClientResponse('pages');
        // check for HTTP_OK
        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());
        // check pagination
        $this->isPaginated($response);
        // Check if data is correctly formatted & everything is returned
        $received = $this->getResponseArray($response)['data'][0];
        $expected = [
            'id' => 'string',
            'type' => 'in:pages',
            'attributes' => [
                'menu_label' => 'string',
                'slug' => 'string',
                'published' => 'bool',
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
    public function get_page_by_id()
    {
        $id = (new App\Api\V1\Models\Page)->first()->id;
        $response = $this->getClientResponse('pages/'.$id);
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
                'published' => 'bool',
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
    public function get_page_by_wrong_id()
    {
        $response = $this->getClientResponse('pages/1');
        // check status code & response body
        $this->checkErrorResponse($response, 'HTTP_NOT_FOUND');
    }
    /**
     * @test
     */
    public function get_related_collections()
    {
        $id = App\Api\V1\Models\Page::all()->first(function($key, $item){
            return count($item->collections) > 0;
        })->id;

        $response = $this->getClientResponse('/pages/'.$id.'/collections');
        // check for HTTP_OK
        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());
        // check pagination
        $this->isPaginated($response);
        // check specific structure & data
        $received = $this->getResponseArray($response)['data'][0];
        $expected = [
            'type' => 'in:collections',
            'id' => 'string',
            'attributes' => 'required'
        ];

        $this->assertValidArray($expected, $received);
    }
    /**
     * @test
     */
    public function get_related_collections_no_relationships()
    {
        $id = App\Api\V1\Models\Page::all()->first(function($key, $item){
            return count($item->collections) === 0;
        })->id;

        $response = $this->getClientResponse('/pages/'.$id.'/collections');

        // check for HTTP_OK
        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());
        // check pagination
        $this->isPaginated($response);
        // check specific structure & data
        $received = $this->getResponseArray($response)['data'];
        $expected = [];

        $this->assertValidArray($expected, $received);
    }
    /**
     * @test
     */
    public function get_related_collections_wrong_id()
    {
        $response = $this->getClientResponse('/pages/1/collections');
        // check status code & response body
        $this->checkErrorResponse($response, 'HTTP_NOT_FOUND');
    }
    /**
     * @test
     */
    public function get_relationships_to_collections()
    {
        $id = App\Api\V1\Models\Page::all()->first(function($key, $item){
            return count($item->collections) > 0;
        })->id;

        $response = $this->getClientResponse('/pages/'.$id.'/relationships/collections');
        // check for HTTP OK
        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());
        // check specific structure & data
        $received = $this->getResponseArray($response)['data'][0];
        $expected = [
            'type' => 'in:collections',
            'id' => 'string'
        ];

        $this->assertValidArray($expected, $received);
    }
    /**
     * @test
     */
    public function get_relationships_to_collections_no_relationships(){
        $id = App\Api\V1\Models\Page::all()->first(function($key, $item){
            return count($item->collections) === 0;
        })->id;
        $response = $this->getClientResponse('/pages/'.$id.'/relationships/collections');
        // check for HTTP OK
        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());
        // check specific structure & data
        $received = $this->getResponseArray($response)['data'];
        $expected = [];

        $this->assertValidArray($expected, $received);
    }
    /**
     * @test
     */
    public function get_relationships_to_collections_wrong_id()
    {
        $response = $this->getClientResponse('/pages/1/relationships/collections');
        // check status code & response body
        $this->checkErrorResponse($response, 'HTTP_NOT_FOUND');
    }
    /**
     * @test
     */
    public function get_related_fragments()
    {
        $id = App\Api\V1\Models\Page::first()->id;
        $response = $this->getClientResponse('/pages/'.$id.'/fragments');
        // check for HTTP_OK
        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());
        // check pagination
        $this->isPaginated($response);
        // check specific structure & data
        $received = $this->getResponseArray($response)['data'][0];
        $expected = [
            'type' => 'in:fragments',
            'id' => 'string',
            'attributes' => 'required'
        ];

        $this->assertValidArray($expected, $received);
    }
    /**
     * @test
     */
    public function get_related_fragments_wrong_id()
    {
        $response = $this->getClientResponse('/pages/1/fragments');
        // check status code & response body
        $this->checkErrorResponse($response, 'HTTP_NOT_FOUND');
    }
    /**
     * @test
     */
    public function get_relationships_to_fragments()
    {
        $id = App\Api\V1\Models\Page::first()->id;
        $response = $this->getClientResponse('/pages/'.$id.'/relationships/fragments');
        // check for HTTP OK
        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());
        // check specific structure & data
        $received = $this->getResponseArray($response)['data'][0];
        $expected = [
            'type' => 'in:fragments',
            'id' => 'string'
        ];

        $this->assertValidArray($expected, $received);
    }
    /**
     * @test
     */
    public function get_relationships_to_fragments_wrong_id()
    {
        $response = $this->getClientResponse('/pages/1/relationships/fragments');
        // check status code & response body
        $this->checkErrorResponse($response, 'HTTP_NOT_FOUND');
    }
}
