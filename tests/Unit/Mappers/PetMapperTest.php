<?php

namespace Tests\Unit\Mappers;

use App\Dtos\CategoryDto;
use App\Dtos\PetDto;
use App\Mappers\PetMapper;
use Illuminate\Support\Collection;
use Tests\TestCase;

final class PetMapperTest extends TestCase
{
    /**
     * @return void
     */
    public function testMapReturnsPetDtoWithCategoryAndTags(): void
    {
        $data = [
            'id' => 1,
            'category' => ['id' => 10, 'name' => 'Dogs'],
            'name' => 'Burek',
            'photoUrls' => ['http://example.com/dog.jpg'],
            'tags' => [
                ['id' => 100, 'name' => 'friendly'],
                ['id' => 101, 'name' => 'playful'],
            ],
            'status' => 'available',
        ];

        $pet = PetMapper::map($data);

        $this->assertInstanceOf(PetDto::class, $pet);
        $this->assertSame(1, $pet->id);
        $this->assertInstanceOf(CategoryDto::class, $pet->category);
        $this->assertSame('Dogs', $pet->category->name);
        $this->assertSame('Burek', $pet->name);
        $this->assertSame(['http://example.com/dog.jpg'], $pet->photoUrls);
        $this->assertIsArray($pet->tags);
        $this->assertSame('available', $pet->status);
    }

    /**
     * @return void
     */
    public function testMapHandlesMissingOptionalFields(): void
    {
        $data = [
            'id' => 2,
            'name' => 'Mruczek',
            'status' => 'pending',
        ];

        $pet = PetMapper::map($data);

        $this->assertInstanceOf(PetDto::class, $pet);
        $this->assertNull($pet->category);
        $this->assertSame([], $pet->photoUrls);
        $this->assertSame([], $pet->tags); // brak tagów
        $this->assertSame('Mruczek', $pet->name);
        $this->assertSame('pending', $pet->status);
    }

    /**
     * @return void
     */
    public function testMapListReturnsCollectionOfPetDtos(): void
    {
        $list = [
            ['id' => 1, 'name' => 'Burek', 'status' => 'available'],
            ['id' => 2, 'name' => 'Mruczek', 'status' => 'pending'],
        ];

        $collection = PetMapper::mapList($list);

        $this->assertInstanceOf(Collection::class, $collection);
        $this->assertCount(2, $collection);
        $this->assertContainsOnlyInstancesOf(PetDto::class, $collection);
        $this->assertSame('Burek', $collection->first()->name);
    }
}
