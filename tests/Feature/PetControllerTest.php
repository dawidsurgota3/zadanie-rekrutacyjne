<?php

namespace Tests\Feature;

use App\Dtos\PetDto;
use App\Exceptions\ApiBadRequestException;
use App\Services\PetService;
use Mockery;
use Tests\TestCase;

/**
 * @param array $overrides
 * @return PetDto
 */
function makePetDto(array $overrides = []): PetDto
{
    return new PetDto(
        id: $overrides['id'] ?? 1,
        category: $overrides['category'] ?? null,
        name: $overrides['name'] ?? 'TestPet',
        photoUrls: $overrides['photoUrls'] ?? ['http://example.com/photo.jpg'],
        tags: $overrides['tags'] ?? [],
        status: $overrides['status'] ?? 'available',
    );
}

class PetControllerTest extends TestCase
{
    /**
     * @return void
     */
    public function testIndexRendersInertiaWithPets(): void
    {
        $mock = Mockery::mock(PetService::class);
        $mock->shouldReceive('getPets')
            ->once()
            ->with('available')
            ->andReturn(collect([
                makePetDto(['id' => 1, 'name' => 'Burek']),
                makePetDto(['id' => 2, 'name' => 'Mruczek']),
            ]));

        $this->app->instance(PetService::class, $mock);

        $this->withoutExceptionHandling();

        $response = $this->get(route('pets.index', ['status' => 'available']));

        $response->assertInertia(fn ($page) =>
        $page->component('pets')
            ->has('pets', 2)
            ->where('pets.0.name', 'Burek')
        );
    }

    /**
     * @return void
     */
    public function testStoreRedirectsBackWithMessage(): void
    {
        $mock = Mockery::mock(PetService::class);
        $mock->shouldReceive('createPet')
            ->once()
            ->with(Mockery::type('array'))
            ->andReturn(makePetDto(['id' => 10, 'name' => 'Reksio']));

        $this->app->instance(PetService::class, $mock);

        $response = $this->post(route('pets.store'), [
            'name' => 'Reksio',
            'status' => 'available',
            'photoUrls' => ['http://example.com/photo.jpg'],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('message', 'Pet created!');
    }

    /**
     * @return void
     */
    public function testUpdateRedirectsBackWithMessage(): void
    {
        $mock = Mockery::mock(PetService::class);
        $mock->shouldReceive('updatePetById')
            ->once()
            ->with(5, Mockery::type('array'))
            ->andReturn(makePetDto(['id' => 5, 'name' => 'Azor', 'status' => 'sold']));

        $this->app->instance(PetService::class, $mock);

        $response = $this->put(route('pets.update', 5), [
            'name' => 'Azor',
            'status' => 'sold',
            'photoUrls' => ['http://example.com/photo.jpg'],
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('message', 'Pet updated!');
    }

    /**
     * @return void
     */
    public function testDestroyRedirectsBackWithMessage(): void
    {
        $mock = Mockery::mock(PetService::class);
        $mock->shouldReceive('deletePetById')
            ->once()
            ->with(7);

        $this->app->instance(PetService::class, $mock);

        $response = $this->delete(route('pets.destroy', 7));

        $response->assertRedirect();
        $response->assertSessionHas('message', 'Pet deleted!');
    }

    /**
     * @return void
     */
    public function testIndexThrowsApiBadRequestException(): void
    {
        $mock = Mockery::mock(PetService::class);
        $mock->shouldReceive('getPets')
            ->once()
            ->andThrow(ApiBadRequestException::class, 'Bad request');

        $this->app->instance(PetService::class, $mock);

        $this->withoutExceptionHandling();
        $this->expectException(ApiBadRequestException::class);

        $this->get(route('pets.index', ['status' => 'sold']));
    }
}
