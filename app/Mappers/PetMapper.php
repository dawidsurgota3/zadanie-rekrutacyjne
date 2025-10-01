<?php

namespace App\Mappers;

use App\Dtos\CategoryDto;
use App\Dtos\PetDto;
use App\Dtos\TagDto;
use Illuminate\Support\Collection;

final class PetMapper
{
    /**
     * @param array $data
     * @return PetDto
     */
    public static function map(array $data): PetDto
    {
        return new PetDto(
            id: $data['id'],
            category: (isset($data['category'])) ? new CategoryDto(
                id: $data['category']['id'] ?? '',
                name: $data['category']['name'] ?? '',
            ) : null,
            name: $data['name'] ?? '',
            photoUrls: $data['photoUrls'] ?? [],
            tags: $data['tags'] ?? array_map(
                fn ($tag) => new TagDto($tag['id'], $tag['name']),
                []
            ),
            status: $data['status'],
        );
    }

    /**
     * @return Collection<int,PetDto>
     */
    public static function mapList(array $list): Collection
    {
        return collect($list)->map(fn ($item) => self::map($item));
    }
}
