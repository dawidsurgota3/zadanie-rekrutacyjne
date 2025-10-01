<?php

namespace Tests\Unit\Services;

use App\Dtos\PetDto;
use App\Exceptions\ApiBadRequestException;
use App\RequestMethod;
use App\Services\PetService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

final class PetServiceTest extends TestCase
{
    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        Config::set('externalservice.pet_service.url', 'https://api.test');
    }

    /**
     * @return void
     * @throws ApiBadRequestException
     */
    public function testGetPetsReturnsCollectionOfPetDtos(): void
    {
        Http::fake([
            'https://api.test/pet/findByStatus*' => Http::response([
                ['id' => 1, 'name' => 'Burek', 'status' => 'available'],
                ['id' => 2, 'name' => 'Mruczek', 'status' => 'available'],
            ], 200),
        ]);

        $service = new PetService();

        $result = $service->getPets('pending');

        $this->assertInstanceOf(Collection::class, $result);
        $this->assertCount(2, $result);
        $this->assertInstanceOf(PetDto::class, $result->first());
        $this->assertSame('Burek', $result->first()->name);
    }

    /**
     * @return void
     * @throws ApiBadRequestException
     */
    public function testGetPetByIdReturnsPetDto(): void
    {
        Http::fake([
            "https://api.test/pet/1" => Http::response([
                'id' => 1,
                'name' => 'Burek',
                'status' => 'available',
            ], 200),
        ]);

        $service = new PetService();

        $pet = $service->getPetById(1);

        $this->assertInstanceOf(PetDto::class, $pet);
        $this->assertSame(1, $pet->id);
        $this->assertSame('Burek', $pet->name);
    }

    /**
     * @return void
     * @throws ApiBadRequestException
     */
    public function testCreatePetReturnsPetDto(): void
    {
        Http::fake([
            'https://api.test/pet' => Http::response([
                'id' => 10,
                'name' => 'Reksio',
                'status' => 'available',
            ], 201),
        ]);

        $service = new PetService();

        $pet = $service->createPet(['name' => 'Reksio']);

        $this->assertInstanceOf(PetDto::class, $pet);
        $this->assertSame('Reksio', $pet->name);
    }

    /**
     * @return void
     * @throws ApiBadRequestException
     */
    public function testUpdatePetByIdReturnsPetDto(): void
    {
        Http::fake([
            "https://api.test/pet" => Http::response([
                'id' => 5,
                'name' => 'Azor',
                'status' => 'sold',
            ], 200),
        ]);

        $service = new PetService();

        $pet = $service->updatePetById(5, ['status' => 'sold']);

        $this->assertInstanceOf(PetDto::class, $pet);
        $this->assertSame('Azor', $pet->name);
        $this->assertSame('sold', $pet->status);
    }

    /**
     * @return void
     * @throws ApiBadRequestException
     */
    public function testDeletePetByIdSendsDeleteRequest(): void
    {
        Http::fake([
            "https://api.test/pet/7" => Http::response([], 204),
        ]);

        $service = new PetService();

       $service->deletePetById(7);

        Http::assertSent(function ($request) {
            return $request->method() === RequestMethod::DELETE->value
                && str_contains($request->url(), '/pet/7');
        });
    }

    /**
     * @return void
     * @throws ApiBadRequestException
     */
    public function testThrowsApiBadRequestExceptionOn400(): void
    {
        Http::fake([
            'https://api.test/pet/findByStatus*' => Http::response(['error' => 'Bad request'], 400),
        ]);

        $service = new PetService();

        $this->expectException(ApiBadRequestException::class);

        $service->getPets('sold');
    }
}
