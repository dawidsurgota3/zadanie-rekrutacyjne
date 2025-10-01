<?php

namespace App\Services\Api;

use App\Exceptions\ApiBadRequestException;
use App\Exceptions\ApiForbiddenException;
use App\Exceptions\ApiNotFoundException;
use App\Exceptions\ApiServerErrorException;
use App\Exceptions\ApiUnauthorizedException;
use App\Exceptions\ApiUnprocessableException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;

class ApiResponseResolver
{
    /**
     * @param Response $response
     * @throws ApiBadRequestException
     * @throws ApiForbiddenException
     * @throws ApiNotFoundException
     * @throws ApiServerErrorException
     * @throws ApiUnauthorizedException
     * @throws ApiUnprocessableException
     */
    public function __construct(
        Response $response,
    )
    {
        Log::warning('Api returned error', [
            'code' => $response->getStatusCode(),
            'status' => $response->status(),
            'body' => $response->body(),
            'json' => $response->json(),
        ]);

        $this->handleError($response);
    }

    /**
     * @param Response $response
     * @return void
     * @throws ApiBadRequestException
     * @throws ApiForbiddenException
     * @throws ApiNotFoundException
     * @throws ApiServerErrorException
     * @throws ApiUnauthorizedException
     * @throws ApiUnprocessableException
     */
    protected function handleError(Response $response): void
    {
        match ($response->status()) {
            400 => throw new ApiBadRequestException($response->body()),
            401 => throw new ApiUnauthorizedException($response->body()),
            403 => throw new ApiForbiddenException($response->body()),
            404 => throw new ApiNotFoundException($response->body()),
            422 => throw new ApiUnprocessableException($response->body()),
            500, 502, 503 => throw new ApiServerErrorException($response->body()),
            default => $response->throw(),
        };
    }
}
