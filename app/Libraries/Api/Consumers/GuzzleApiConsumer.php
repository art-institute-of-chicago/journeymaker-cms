<?php

namespace App\Libraries\Api\Consumers;

use App\Libraries\Api\Exceptions\JsonException;
use GuzzleHttp\Client;

class GuzzleApiConsumer implements ApiConsumerInterface
{
    private $client;

    public function __construct($options = [])
    {
        $this->client = new Client($options);
    }

    /**
     * Intercept the Guzzle request and return a cleaner object
     */
    public function request($method, $uri = '', array $options = [])
    {
        $response = $this->client->request($method, $uri, $options);
        $contents = $response->getBody()->getContents();

        if (! json_validate($contents)) {
            throw new JsonException('Invalid JSON: '.$contents);
        }

        return (object) [
            'body' => json_decode($contents),
            'status' => $response->getStatusCode(),
        ];
    }

    /**
     * Adapt raw parameters to be implemented correctly by the client library.
     * You can send parameters directly, or adapt them manually.
     * This method should be defined for each consumer to ease configuration.
     */
    public function adaptParameters($params)
    {
        return ['body' => json_encode($params)];
    }

    /**
     * Add a default header and merge with headers coming from the parameters
     */
    public function headers($params)
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Accept-Encoding' => 'gzip',
        ];

        if (config('api.token')) {
            $headers = array_merge($headers, [
                'Authorization' => 'Bearer '.config('api.token'),
            ]);
        }

        if (isset($params['headers'])) {
            $headers = array_merge($headers, $params['headers']);
        }

        return ['headers' => $headers];
    }
}
