<?php

use Lukasoppermann\Httpstatus\Httpstatuscodes;
use Lukasoppermann\Testing\TestTrait;

class TestCase extends Laravel\Lumen\Testing\TestCase implements Httpstatuscodes
{
    use TestTrait;

    protected $client;

    public function setUp()
    {
        parent::setUp();

        $this->client = new GuzzleHttp\Client([
            'base_uri' => 'http://api.formandsystem.app',
            'exceptions' => false,
        ]);
    }
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }
    /**
     * Decode json response to array
     *
     * @return array
     */
    public function getResponseArray($response)
    {
        return json_decode($response->getBody()->getContents(), true);
    }
    /**
     * Decode json response to array
     *
     * @return array
     */
    public function assertArray($expected, $actual)
    {
        $specialValues = ['is_int', 'is_string'];

        foreach($expected as $key => $value){
            if( !array_key_exists($key, $actual) ){
                return false;
            }
            if( $value === 'is_int' ){
                return is_int($actual[$key]);
            }
            if( $value === 'is_string' ){
                return is_string($value === $actual[$key]);
            }
            if( !in_array($value, $specialValues) ){
                return ($value === $actual[$key]);
            }
        }

        $this->assertTrue($passes);
    }
    /**
     * get the response from server
     */
    public function getClientResponse($url, $headers = [])
    {
        return $this->client->get($url, [
            'headers' => array_merge([
                'Accept' => 'application/json',
            ], $headers),
        ]);
    }
    /**
     * test error response
     */
    public function checkErrorResponse($response, $errorType){
        $received = $this->getResponseArray($response);
        // check status
        $this->assertEquals(constant("self::$errorType"), $response->getStatusCode());

        $expected = [
            'error' => [
                'message' => 'string',
                'status_code' => 'integer|in:'.constant("self::$errorType")
            ]
        ];

        $this->assertValidArray($expected, $received);
    }
}
