<?php

class Metadetail_postTest extends TestCase
{
    /**
     * @test
     */
    public function post_metadetail()
    {
        $response = $this->client->request('POST', '/metadetails', [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'body' => json_encode([
                "data" => [
                    "type" => "language",
                    "value" => [
                        "name" => "deutsch",
                        "key" => "de"
                    ]
                ]
            ])
        ]);
        // get content from response
        $id = json_decode($response->getBody(), true)['data']['id'];
        // check status code & response body
        $this->assertEquals(self::HTTP_CREATED, $response->getStatusCode());
        $this->assertNotNull(App\Api\V1\Models\Metadetail::find($id));
    }
    /**
     * @test
     */
    public function post_metadetail_missing_data()
    {
        $response = $this->client->request('POST', '/metadetails', [
            'headers' => [
                'Accept' => 'application/json',
            ]
        ]);
        // check status code & response body
        $this->assertEquals(self::HTTP_CREATED, $response->getStatusCode());
        $this->assertNotNull(App\Api\V1\Models\Metadetail::find($id));
    }
}
