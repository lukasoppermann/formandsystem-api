<?php

class PageTest extends TestCase
{
    /**
     * @test
     */
    public function get_a_page_by_id()
    {
        $response = $this->client->get('/pages/1', [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);
        print_r($this->getResponseArray($response));
        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());

        $expected = [
            'id' => 'string',
            'type' => 'in:navigation,news',
            'attributes' => [
                'page_id' => 'string',
                'position' => 'integer',
            ]
        ];

        // $this->assertValidArray($expected, $this->getResponseArray($response)['data'][0]);


    }
}
