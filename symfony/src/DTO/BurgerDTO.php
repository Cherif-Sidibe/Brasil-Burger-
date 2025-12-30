<?php

namespace App\DTO;

use App\Entity\Burger;

final readonly class BurgerDTO
{
    public function __construct(
        public int $id,
        public string $nom,
        public string $prix,
        public ?string $description,
        public ?string $image,
        public bool $isArchive,
        public \DateTimeInterface $createdAt,
        public \DateTimeInterface $updatedAt,
        public int $totalVentes = 0,
    ) {}

    public static function fromEntity(Burger $burger, int $totalVentes = 0): self
    {
        return new self(
            id: $burger->getId(),
            nom: $burger->getNom(),
            prix: $burger->getPrix(),
            description: $burger->getDescription(),
            image: $burger->getImage(),
            isArchive: $burger->isArchive(),
            createdAt: $burger->getCreatedAt(),
            updatedAt: $burger->getUpdatedAt(),
            totalVentes: $totalVentes,
        );
    }

    /**
     * @param Burger[] $burgers
     * @return self[]
     */
    public static function fromEntities(array $burgers): array
    {
        return array_map(fn(Burger $burger) => self::fromEntity($burger), $burgers);
    }
}
