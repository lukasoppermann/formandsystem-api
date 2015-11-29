<?php

class CollectionTest extends TestCase
{
    /**
     * @test
     */
    public function get_a_collection_by_type()
    {
        $response = $this->client->get('/collection/navigation', [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());

        $expected = [
            'id' => 'string',
            'type' => 'in:navigation,news',
            'attributes' => [
                'page_id' => 'string',
                'position' => 'integer',
            ]
        ];

        $this->assertValidArray($expected, $this->getResponseArray($response)['data'][0]);


    }
}
