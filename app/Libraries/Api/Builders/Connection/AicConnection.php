<?php

namespace App\Libraries\Api\Builders\Connection;

use App\Libraries\Api\Builders\Grammar\AicGrammar;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use stdClass;

class AicConnection implements ApiConnectionInterface
{
    protected $client;

    protected $queryGrammar;

    /**
     * Create a new API connection instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->client = App::make('ApiClient');
        $this->queryGrammar = new AicGrammar();
    }

    public function getQueryGrammar()
    {
        return $this->queryGrammar;
    }

    /**
     * Run a get statement against the API.
     */
    public function get(string $endpoint, array $params): stdClass
    {
        return $this->execute($endpoint, $params);
    }

    /**
     * Execute a general call to the API client
     */
    public function execute(?string $endpoint = null, array $params = []): stdClass
    {
        $headers = $this->client->headers($params);
        $options = $headers;

        $queryKeys = ['ids', 'include'];
        $bodyParams = Arr::except($params, $queryKeys);

        $verb = empty($bodyParams) ? 'GET' : 'POST';

        if ($verb === 'GET') {
            if ($params !== []) {
                // WEB-979: See DecodeParams middleware in data-aggregator
                $endpoint = $endpoint.'?params='.urlencode(json_encode($params));
            }
        } elseif (! empty($bodyParams)) {
            $adaptedParameters = $this->client->adaptParameters($params);
            $options = array_merge($adaptedParameters, $headers);
        }

        $response = $this->client->request($verb, $endpoint, $options);

        return $response;
    }
}
