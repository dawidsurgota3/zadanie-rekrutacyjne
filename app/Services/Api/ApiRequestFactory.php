<?php

namespace App\Services\Api;

class ApiRequestFactory
{
    /**
     * @param string $baseUrl
     * @return ApiRequestBuilder
     */
    public static final function make(string $baseUrl): ApiRequestBuilder
    {
        return new ApiRequestBuilder($baseUrl);
    }
}
