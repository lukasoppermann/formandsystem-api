<?php

class FragmentTest extends TestCase
{
    /**
     * @test
     */
    public function get_fragments()
    {
        $response = $this->getClientResponse('fragments');
        // check for HTTP_OK
        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());
        // check pagination
        $this->isPaginated($response);
        // Check if data is correctly formatted & everything is returned
        $received = $this->getResponseArray($response)['data'][0];
        $expected = [
            'id' => 'string',
            'type' => 'in:fragments',
            'attributes' => [
                'name' => 'string|not_required',
                'type' => 'string',
                'data' => 'string',
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
    public function get_fragment_by_id()
    {
        $id = (new App\Api\V1\Models\Fragment)->first()->id;
        $response = $this->getClientResponse('fragments/'.$id);
        // check for HTTP_OK
        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());
        // Check if data is correctly formatted & everything is returned
        $received = $this->getResponseArray($response)['data'];
        $expected = [
            'id' => 'string',
            'type' => 'in:fragments',
            'attributes' => [
                'name' => 'string|not_required',
                'type' => 'string',
                'data' => 'string',
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
    public function get_fragment_by_wrong_id()
    {
        $response = $this->getClientResponse('fragments/1');
        // check status code & response body
        $this->checkErrorResponse($response, 'HTTP_NOT_FOUND');
    }
    /**
     * @test
     */
    public function get_related_fragments()
    {
        $id = App\Api\V1\Models\Fragment::all()->first(function($key, $item){
            return count($item->fragments) > 0;
        })->id;
        $response = $this->getClientResponse('/fragments/'.$id.'/fragments');
        // check for HTTP OK
        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());
        // check pagination
        $this->isPaginated($response);
        // check specific structure & data
        $received = $this->getResponseArray($response);
        $expected = [
            'data' => [
                0 => [
                    'type' => 'in:fragments',
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
    public function get_related_fragments_wrong_id()
    {
        $response = $this->getClientResponse('fragments/1/fragments');
        // check status code & response body
        $this->checkErrorResponse($response, 'HTTP_NOT_FOUND');
    }
    /**
     * @test
     */
    public function get_relationships_to_fragments()
    {
        $id = App\Api\V1\Models\Fragment::all()->first(function($key, $item){
            return count($item->fragments) > 0;
        })->id;

        $response = $this->getClientResponse('/fragments/'.$id.'/relationships/fragments');
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
        $response = $this->getClientResponse('/fragments/1/relationships/fragments');
        // check status code & response body
        $this->checkErrorResponse($response, 'HTTP_NOT_FOUND');
    }
}
