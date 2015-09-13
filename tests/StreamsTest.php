<?php

class StreamsTest extends TestCase
{
    /**
     * @test
     */
    public function get_a_stream_by_type()
    {
        $response = $this->client->get('/streams/navigation', [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());

        // $this->assertValidArray(['id' => 'integer', 'string' => 'string'], ['id2' => 'Test', 'string' => 1]);

        $expected = [
            'id' => 'string',
            'type' => 'in:navigation',
            // 'attributes' => [
            //     'page_id' => 'string',
            //     'position' => 'integer',
            // ]
        ];
        // $this->assertValidArray($expected, $this->getResponseArray($response)['data'][0]);


    }
}
