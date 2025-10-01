<?php

namespace Tests\Unit\Services\Api;

use App\Exceptions\ApiBadRequestException;
use App\RequestMethod;
use App\Services\Api\ApiRequestBuilder;
use Illuminate\Support\Facades\Http;
use LogicException;
use Tests\TestCase;

final class ApiRequestBuilderTest extends TestCase
{
    /**
     * @return void
     * @throws ApiBadRequestException
     */
    public function testSendGetRequestReturnsJson(): void
    {
        Http::fake([
            'https://api.test/users*' => Http::response(['id' => 1, 'name' => 'Dawid']),
        ]);

        $builder = new ApiRequestBuilder('https://api.test');

        $result = $builder
            ->endpoint('/users')
            ->method(RequestMethod::GET)
            ->params(['active' => true])
            ->send();

        $this->assertSame(['id' => 1, 'name' => 'Dawid'], $result);
    }

    /**
     * @return void
     * @throws ApiBadRequestException
     */
    public function testSendPostRequestWithBody(): void
    {
        Http::fake([
            'https://api.test/users' => Http::response(['created' => true], 201),
        ]);

        $builder = new ApiRequestBuilder('https://api.test');

        $result = $builder
            ->endpoint('/users')
            ->method(RequestMethod::POST)
            ->body(['name' => 'Dawid'])
            ->send();

        $this->assertSame(['created' => true], $result);
    }

    /**
     * @return void
     * @throws ApiBadRequestException
     */
    public function testThrowsLogicExceptionIfEndpointOrMethodMissing(): void
    {
        $this->expectException(LogicException::class);

        $builder = new ApiRequestBuilder('https://api.test');
        $builder->send();
    }

    /**
     * @return void
     * @throws ApiBadRequestException
     */
    public function testThrowsApiBadRequestExceptionOn400(): void
    {
        Http::fake([
            'https://api.test/users' => Http::response(['error' => 'Bad request'], 400),
        ]);

        $builder = new ApiRequestBuilder('https://api.test');

        $this->expectException(ApiBadRequestException::class);

        $builder
            ->endpoint('/users')
            ->method(RequestMethod::GET)
            ->send();

    }
}
