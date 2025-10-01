<?php

namespace Tests\Unit\Services\Api;

use App\Exceptions\ApiBadRequestException;
use App\Exceptions\ApiForbiddenException;
use App\Exceptions\ApiNotFoundException;
use App\Exceptions\ApiServerErrorException;
use App\Exceptions\ApiUnauthorizedException;
use App\Exceptions\ApiUnprocessableException;
use App\Services\Api\ApiResponseResolver;
use Illuminate\Http\Client\Response;
use Tests\TestCase;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use PHPUnit\Framework\Attributes\DataProvider;

final class ApiResponseResolverTest extends TestCase
{
    #[DataProvider('errorResponses')]
    public function testItThrowsExpectedExceptionForStatus(int $status, string $expectedException): void
    {
        $this->expectException($expectedException);

        $guzzle = new GuzzleResponse($status, [], json_encode(['error' => 'Something went wrong']));
        $response = new Response($guzzle);

        new ApiResponseResolver($response);
    }

    public static function errorResponses(): array
    {
        return [
            '400 Bad Request'      => [400, ApiBadRequestException::class],
            '401 Unauthorized'     => [401, ApiUnauthorizedException::class],
            '403 Forbidden'        => [403, ApiForbiddenException::class],
            '404 Not Found'        => [404, ApiNotFoundException::class],
            '422 Unprocessable'    => [422, ApiUnprocessableException::class],
            '500 Internal Error'   => [500, ApiServerErrorException::class],
            '502 Bad Gateway'      => [502, ApiServerErrorException::class],
            '503 Service Unavail.' => [503, ApiServerErrorException::class],
        ];
    }
}
