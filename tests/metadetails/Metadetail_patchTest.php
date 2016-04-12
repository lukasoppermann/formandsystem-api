<?php

class Metadetail_patchTest extends TestCase
{
    /**
     * @test
     */
    public function patch_metadetail()
    {
        $id = App\Api\V1\Models\Metadetail::first()->id;
        $response = $this->client->request('PATCH', '/metadetails/'.$id, [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'body' => json_encode([
                "data" => [
                    "id" => $id,
                    "type" => "metadetails",
                    "attributes" => [
                        "type" => "language",
                        "value" => [
                            "name" => "englisch",
                            "key" => "en"
                        ]
                    ]
                ]
            ])
        ]);
        // expected results
        $expected = [
            "type" => "in:metadetails",
            "id" => "in:".$id,
            "attributes" => [
                "type" => "in:language",
                "value" => [
                    "name" => "in:englisch",
                    "key" => "in:en",
                ],
                "created_at" => "string",
                "updated_at" => "string",
            ],
            "links" => [
                "self" => "in:".$_ENV['API_DOMAIN']."/metadetails/".$id
            ],
            "relationships" => [
                "pages" => [
                    "links" => [
                        "self" => "string"
                    ],
                ]
            ]
        ];
        // check status code & response body
        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());
        $this->assertValidArray($expected, json_decode($response->getBody(), true)['data']);
    }
    /**
     * @test
     */
    public function patch_metadetail_missing_type()
    {
        $id = App\Api\V1\Models\Metadetail::first()->id;
        $response = $this->client->request('PATCH', '/metadetails/'.$id, [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'body' => json_encode([
                "data" => [
                    "id" => $id,
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
        // check status code & response body
        $this->assertEquals(self::HTTP_FORBIDDEN, $response->getStatusCode());
    }
    /**
     * @test
     */
    public function patch_metadetail_missing_body()
    {
        $id = App\Api\V1\Models\Metadetail::first()->id;
        $response = $this->client->request('PATCH', '/metadetails/'.$id, [
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
    public function patch_metadetail_wrong_id()
    {
        $id = App\Api\V1\Models\Metadetail::first()->id;
        $response = $this->client->request('PATCH', '/metadetails/1', [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'body' => json_encode([
                "data" => [
                    "type" => "metadetails",
                    "id" => $id,
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
        // check status code & response body
        $this->assertEquals(self::HTTP_NOT_FOUND, $response->getStatusCode());
    }
    /**
     * @test
     */
    public function patch_metadetail_body_relationships_array()
    {
        // PREPARE
        $id = App\Api\V1\Models\Metadetail::first()->id;
        $model = App\Api\V1\Models\Metadetail::find($id);
        $model->pages()->detach();
        $model->pages()->save(App\Api\V1\Models\Page::first());
        $this->assertEquals(1, $model->pages()->count());
        // PATCH
        $response = $this->client->request('PATCH', '/metadetails/'.$id, [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'body' => json_encode([
                "data" => [
                    "type" => "metadetails",
                    "id" => $id,
                    "relationships" => [
                        "pages" => [
                            "data" => [
                                [
                                    'type' => 'pages',
                                    'id'   => App\Api\V1\Models\Page::all()->random(1)->id
                                ],
                                [
                                    'type' => 'pages',
                                    'id'   => App\Api\V1\Models\Page::all()->random(1)->id
                                ]
                            ]
                        ]
                    ]
                ]
            ])
        ]);
        // check status code & response body
        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(2, $model->pages()->count());
    }
    /**
     * @test
     */
    public function patch_metadetail_body_relationships_null()
    {
        // PREPARE
        $id = App\Api\V1\Models\Metadetail::first()->id;
        $model = App\Api\V1\Models\Metadetail::find($id);
        $model->pages()->save(App\Api\V1\Models\Page::first());
        $relatedCount = $model->pages()->count();
        // PATCH
        $response = $this->client->request('PATCH', '/metadetails/'.$id, [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'body' => json_encode([
                "data" => [
                    "type" => "metadetails",
                    "id" => $id,
                    "relationships" => [
                        "pages" => [
                            "data" => []
                        ]
                    ]
                ]
            ])
        ]);
        // check status code & response body
        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());
        $this->assertNotEquals($relatedCount, $model->pages()->count());
        $this->assertEquals(0,$model->pages()->count());
    }
    /**
     * @test
     */
    public function patch_metadetail_body_relationships_single()
    {
        // PREPARE
        $id = App\Api\V1\Models\Metadetail::first()->id;
        $model = App\Api\V1\Models\Metadetail::find($id);
        $model->pages()->detach();
        $model->pages()->save(App\Api\V1\Models\Page::all()->random(1));
        $model->pages()->save(App\Api\V1\Models\Page::all()->random(1));
        $this->assertEquals(2, $model->pages()->count());
        // PATCH
        $response = $this->client->request('PATCH', '/metadetails/'.$id, [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'body' => json_encode([
                "data" => [
                    "type" => "metadetails",
                    "id" => $id,
                    "relationships" => [
                        "pages" => [
                            "data" => [
                                'type' => 'pages',
                                'id'   => App\Api\V1\Models\Page::all()->random(1)->id
                            ]
                        ]
                    ]
                ]
            ])
        ]);
        // check status code & response body
        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(1, $model->pages()->count());
    }
    /**
     * @test
     */
    public function patch_metadetail_body_wrong_relationships()
    {
        // PREPARE
        $id = App\Api\V1\Models\Metadetail::first()->id;
        $model = App\Api\V1\Models\Metadetail::find($id);
        $model->pages()->detach();
        $model->pages()->save(App\Api\V1\Models\Page::all()->random(1));
        $model->pages()->save(App\Api\V1\Models\Page::all()->random(1));
        $this->assertEquals(2, $model->pages()->count());
        // PATCH
        $response = $this->client->request('PATCH', '/metadetails/'.$id, [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'body' => json_encode([
                "data" => [
                    "type" => "metadetails",
                    "id" => $id,
                    "relationships" => [
                        "pages" => [
                            "data" => [
                                'type' => 'pages',
                                'id'   => "2"
                            ]
                        ],
                        "test" => [
                            "data" => [
                                'type' => 'test',
                                'id'   => "1"
                            ]
                        ]
                    ]
                ]
            ])
        ]);
        // check status code & response body
        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(0, $model->pages()->count());
    }
    /**
     * @test
     */
    public function patch_metadetail_relationships_single()
    {
        // PREPARE
        $id = App\Api\V1\Models\Metadetail::first()->id;
        // $model = App\Api\V1\Models\Metadetail::find($id);
        // $model->pages()->detach();
        // $model->pages()->save(App\Api\V1\Models\Page::all()->random(1));
        // $model->pages()->save(App\Api\V1\Models\Page::all()->random(1));
        // $this->assertEquals(2, $model->pages()->count());
        // PATCH
        $response = $this->client->request('PATCH', '/metadetails/'.$id.'/relationships/pages', [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'body' => json_encode([
                "data" => [
                    'type' => 'pages',
                    'id'   => App\Api\V1\Models\Page::all()->random(1)->id
                ]
            ])
        ]);
        // check status code & response body
        $this->assertEquals(self::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(1, $model->pages()->count());
    }
}
