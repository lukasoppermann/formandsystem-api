<?php

namespace Lukasoppermann\Testing\Traits;

trait CallTrait
{
    protected function client()
    {
        return new \GuzzleHttp\Client([
            'base_uri' => $this->url,
            'exceptions' => false,
        ]);
    }

    protected function headers($headers = [])
    {
        return array_merge([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$this->token('client'),
            'Testing' => 'true',
        ], $headers);
    }
    /**
     * get token
     */
    protected function token($type = 'client')
    {
        return "12345";
    }
    /**
     * get the response from server
     */
    public function getCall($url, $headers = [])
    {
        return $this->client()->get($url, [
            'headers' => $this->headers($headers),
        ]);
    }
}
