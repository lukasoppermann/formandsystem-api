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
    public function get_a_fragment_by_id()
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
    public function get_a_fragment_by_wrong_id()
    {
        $response = $this->getClientResponse('fragments/1');
        // check status code & response body
        $this->checkErrorResponse($response, 'HTTP_NOT_FOUND');
    }
    /**
     * @test
     */
    public function get_fragments_fragments()
    {
        $this->fail('Test missing!');
    }
    /**
     * @test
     */
    public function get_fragments_from_fragment_wrong_id()
    {
        $response = $this->getClientResponse('fragments/1/fragments');
        // check status code & response body
        $this->checkErrorResponse($response, 'HTTP_NOT_FOUND');
    }
    /**
     * @test
     */
    public function get_fragments_relationship_fragments()
    {
        $this->fail('Test missing!');
    }
    /**
     * @test
     */
    public function get_fragments_relationship_from_fragment_wrong_id()
    {
        $response = $this->getClientResponse('/fragments/1/relationships/fragments');
        // check status code & response body
        $this->checkErrorResponse($response, 'HTTP_NOT_FOUND');
    }
}
