<?php

namespace App\Services\Api;


use App\Exceptions\ApiBadRequestException;
use App\RequestMethod;

readonly class ApiService implements ApiServiceInterface
{
    public function __construct(
        private string $baseUrl
    ) {}

    /**
     * @param string $endpoint
     * @param RequestMethod $method
     * @param array $params
     * @param array $body
     * @return mixed
     * @throws ApiBadRequestException
     */
    public final function request(string $endpoint, RequestMethod $method, array $params = [], array $body = []): mixed
    {
        return ApiRequestFactory::make($this->baseUrl)
            ->endpoint($endpoint)
            ->method($method)
            ->params($params)
            ->body($body)
            ->send();
    }
}
