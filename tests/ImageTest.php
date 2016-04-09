<?php

class ImageTest extends TestCase
{
    /**
     * single item
     */
    public function expected($received){
        return [
            'id' => 'string',
            'type' => 'in:images',
            'attributes' => [
                'link' => 'url',
                'slug' => 'string',
                'bytesize' => 'integer',
                'width' => 'integer',
                'height' => 'integer',
            ],
            'links' => [
                'self' => 'in:'.$_ENV['API_DOMAIN'].'/'.$received['type'].'/'.$received['id']
            ]
        ];
    }
    /**
     * @test
     */
    public function get_images()
    {
        $response = $this->getClientResponse('images');
        // check for HTTP_OK
        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());
        // check pagination
        $this->isPaginated($response);
        // Check if data is correctly formatted & everything is returned
        $received = $this->getResponseArray($response)['data'][0];
        $expected = $this->expected($received);
        $this->assertValidArray($expected, $received);
    }
    /**
     * @test
     */
    public function get_image_by_id()
    {
        $id = (new App\Api\V1\Models\Image)->first()->id;
        $response = $this->getClientResponse('images/'.$id);
        // check for HTTP_OK
        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());
        // Check if data is correctly formatted & everything is returned
        $received = $this->getResponseArray($response)['data'];
        $expected = $this->expected($received);
        $this->assertValidArray($expected, $received);
    }
    /**
     * @test
     */
    public function get_image_by_wrong_id()
    {
        $response = $this->getClientResponse('images/1');
        // check status code & response body
        $this->checkErrorResponse($response, 'HTTP_NOT_FOUND');
    }
    /**
     * @test
     */
    public function get_related_fragments()
    {
        $id = App\Api\V1\Models\Image::first()->id;
        $response = $this->getClientResponse('/images/'.$id.'/fragments');
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
        $response = $this->getClientResponse('images/1/fragments');
        // check status code & response body
        $this->checkErrorResponse($response, 'HTTP_NOT_FOUND');
    }
    /**
     * @test
     */
    public function get_relationships_to_fragments()
    {
        $id = App\Api\V1\Models\Image::first()->id;
        $response = $this->getClientResponse('/images/'.$id.'/relationships/fragments');
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
        $response = $this->getClientResponse('images/1/relationships/fragments');
        // check status code & response body
        $this->checkErrorResponse($response, 'HTTP_NOT_FOUND');
    }
}
