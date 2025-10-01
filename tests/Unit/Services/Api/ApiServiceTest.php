<?php

namespace Tests\Unit\Services\Api;

use App\Exceptions\ApiBadRequestException;
use App\RequestMethod;
use App\Services\Api\ApiService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

final class ApiServiceTest extends TestCase
{
    /**
     * @return void
     * @throws ApiBadRequestException
     */
    public function testRequestReturnsJsonOnSuccess(): void
    {
        Http::fake([
            'https://api.test/users*' => Http::response(['id' => 1, 'name' => 'Dawid'], 200),
        ]);

        $service = new ApiService('https://api.test');

        $result = $service->request('/users', RequestMethod::GET, ['active' => true]);

        $this->assertSame(['id' => 1, 'name' => 'Dawid'], $result);
    }

    /**
     * @return void
     * @throws ApiBadRequestException
     */
    public function testRequestSendsPostBody(): void
    {
        Http::fake([
            'https://api.test/users' => Http::response(['created' => true], 201),
        ]);

        $service = new ApiService('https://api.test');

        $result = $service->request('/users', RequestMethod::POST, [], ['name' => 'Dawid']);

        $this->assertSame(['created' => true], $result);
    }

    /**
     * @return void
     * @throws ApiBadRequestException
     */
    public function testRequestThrowsApiBadRequestExceptionOn400(): void
    {
        Http::fake([
            'https://api.test/users' => Http::response(['error' => 'Bad request'], 400),
        ]);

        $service = new ApiService('https://api.test');

        $this->expectException(ApiBadRequestException::class);

        $service->request('/users', RequestMethod::GET);
    }
}
