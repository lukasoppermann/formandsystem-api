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
                    "type" => "metadetails",
                    "attributes" => [
                        "type" => "language",
                        "value" => [
                            "name" => "deutsch",
                            "key" => "de"
                        ]
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
        $this->assertEquals(self::HTTP_FORBIDDEN, $response->getStatusCode());
    }
    /**
     * @test
     */
    public function post_metadetail_relationship_pages_missing_data()
    {
        $id = App\Api\V1\Models\Metadetail::first()->id;
        $response = $this->client->request('POST', '/metadetails/'.$id.'/relationships/pages', [
            'headers' => [
                'Accept' => 'application/json',
            ]
        ]);
        // check status code & response body
        $this->assertEquals(self::HTTP_FORBIDDEN, $response->getStatusCode());
    }
    /**
     * @test
     */
    public function post_metadetail_relationship_pages_wrong_data()
    {
        $id = App\Api\V1\Models\Metadetail::first()->id;
        $response = $this->client->request('POST', '/metadetails/'.$id.'/relationships/pages', [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'body' => json_encode(["data" => [
                [
                    "type" => "pages",
                ],
                    "id" => "1"
                ]])
        ]);
        // check status code & response body
        $this->assertEquals(self::HTTP_FORBIDDEN, $response->getStatusCode());
    }
}
