<?php

class Metadetail_getTest extends TestCase
{
    /**
     * single item
     */
    public function expected($received){
        return [
            'id' => 'string',
            'type' => 'in:metadetails',
            'attributes' => [
                'type' => 'string',
                'value' => 'required',
            ],
            'links' => [
                'self' => 'in:'.$_ENV['API_DOMAIN'].'/'.$received['type'].'/'.$received['id']
            ]
        ];
    }
    /**
     * @test
     */
    public function get_metadetails()
    {
        $response = $this->getClientResponse('metadetails');
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
    public function get_meta_by_id()
    {
        $id = App\Api\V1\Models\Metadetail::all()->first()->id;

        $response = $this->getClientResponse('metadetails/'.$id);
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
    public function get_meta_by_wrong_id()
    {
        $response = $this->getClientResponse('metadetails/1');
        // check status code & response body
        $this->checkErrorResponse($response, 'HTTP_NOT_FOUND');
    }
    /**
     * @test
     */
    public function get_meta_by_type()
    {
        $type = App\Api\V1\Models\Metadetail::first()->type;
        $response = $this->getClientResponse('/metadetails?filter=[type='.$type.']');
        // check for HTTP_OK
        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());
        // Check if data is correctly formatted & everything is returned
        $received = $this->getResponseArray($response)['data'][0];
        $this->assertValidArray($this->expected($received), $received);
    }
    /**
     * @test
     */
    public function get_meta_by_wrong_type()
    {
        $response = $this->getClientResponse('file?filter=[type=wrongtype]');
        // check status code & response body
        $this->checkErrorResponse($response, 'HTTP_NOT_FOUND');
    }
    /**
     * @test
     */
    public function get_related_pages()
    {
        $id = App\Api\V1\Models\Metadetail::all()->first(function($key, $item){
            return count($item->pages) > 0;
        })->id;
        $response = $this->getClientResponse('/metadetails/'.$id.'/pages');
        // check for HTTP OK
        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());
        // check pagination
        $this->isPaginated($response);
        // check specific structure & data
        $received = $this->getResponseArray($response);
        $expected = [
            'data' => [
                0 => [
                    'type' => 'in:pages',
                    'id' => 'string',
                    'attributes' => 'required'
                ]
            ]
        ];

        $this->assertValidArray($expected, $received);
    }
    /**
     * @test
     */
    public function get_related_pages_wrong_id()
    {
        $response = $this->getClientResponse('metadetails/1/pages');
        // check status code & response body
        $this->checkErrorResponse($response, 'HTTP_NOT_FOUND');
    }
    /**
     * @test
     */
    public function get_relationships_to_pages()
    {
        $id = App\Api\V1\Models\Metadetail::all()->first(function($key, $item){
            return count($item->pages) > 0;
        })->id;

        $response = $this->getClientResponse('/metadetails/'.$id.'/relationships/pages');
        // check for HTTP OK
        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());
        // check specific structure & data
        $received = $this->getResponseArray($response)['data'][0];
        $expected = [
            'type' => 'in:pages',
            'id' => 'string'
        ];

        $this->assertValidArray($expected, $received);
    }
    /**
     * @test
     */
    public function get_relationships_to_pages_wrong_id()
    {
        $response = $this->getClientResponse('metadetails/1/relationships/pages');
        // check status code & response body
        $this->checkErrorResponse($response, 'HTTP_NOT_FOUND');
    }
}
