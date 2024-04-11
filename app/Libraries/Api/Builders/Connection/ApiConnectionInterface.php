<?php

namespace App\Libraries\Api\Builders\Connection;

interface ApiConnectionInterface
{
    public function __construct();

    public function get(string $endpoint, array $params);

    public function execute(?string $endpoint = null, array $params = []);
}
