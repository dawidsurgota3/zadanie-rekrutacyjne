<?php

namespace App\Dtos;

final class PetDto
{
    public function __construct(
        public int $id,
        public ?CategoryDto $category,
        public string $name,
        /**
         * @var string[]
         */
        public array $photoUrls,
        /**
         * @var TagDto[]
         */
        public array $tags,
        public string $status,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            category: CategoryDto::fromArray($data['category']),
            name: $data['name'],
            photoUrls: $data['photoUrls'] ?? [],
            tags: array_map(fn ($tag) => TagDto::fromArray($tag), $data['tags'] ?? []),
            status: $data['status'],
        );
    }
}
