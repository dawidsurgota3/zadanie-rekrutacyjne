<?php

namespace App\Services\Api;

use App\RequestMethod;

interface ApiServiceInterface
{
    /**
     * @param string $endpoint
     * @param RequestMethod $method
     * @param array $params
     * @param array $body
     * @return mixed
     */
    public function request(string $endpoint, RequestMethod $method, array $params = [], array $body = []): mixed;
}
