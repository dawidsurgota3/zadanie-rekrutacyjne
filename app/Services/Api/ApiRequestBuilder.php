<?php

namespace App\Services\Api;

use App\Exceptions\ApiBadRequestException;
use App\RequestMethod;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use LogicException;

final class ApiRequestBuilder
{
    /**
     * @var PendingRequest
     */
    private PendingRequest $client;

    /**
     * @var string
     */
    private string $endpoint;

    /**
     * @var RequestMethod
     */
    private RequestMethod $method;

    /**
     * @var array
     */
    private array $params = [];

    /**
     * @var array
     */
    private array $body = [];

    /**
     * @param string $baseUrl
     */
    public function __construct(string $baseUrl)
    {
        $this->client = Http::baseUrl($baseUrl)
            ->acceptJson()
            ->timeout(10)
            ->retry(3, 200, throw: false);
    }

    /**
     * @param string $endpoint
     * @return $this
     */
    public function endpoint(string $endpoint): self
    {
        $this->endpoint = $endpoint;
        return $this;
    }

    /**
     * @param RequestMethod $method
     * @return $this
     */
    public function method(RequestMethod $method): self
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @param array $params
     * @return $this
     */
    public function params(array $params): self
    {
        $this->params = $params;

        return $this;
    }

    /**
     * @param array $body
     * @return $this
     */
    public function body(array $body): self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @return mixed
     * @throws ApiBadRequestException
     */
    public final function send(): array
    {
        if (!isset($this->endpoint, $this->method)) {
            throw new LogicException('Endpoint and method must be set before sending request.');
        }

        /**
         * @var Response $response
         */
        $response = $this->client->{$this->method->value}(
            $this->endpoint,
            $this->method === RequestMethod::GET ? $this->params : $this->body
        );

        if ($response->failed()) {
            new ApiResponseResolver($response);
        }

        Log::info('response: ' . json_encode($response->json()));

        return $response->json();
    }
}

