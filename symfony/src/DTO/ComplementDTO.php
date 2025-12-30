<?php

namespace App\DTO;

use App\Entity\Complement;

final readonly class ComplementDTO
{
    public function __construct(
        public int $id,
        public string $nom,
        public string $typeComplement,
        public string $prix,
        public ?string $description,
        public ?string $image,
        public bool $isArchive,
        public \DateTimeInterface $createdAt,
        public \DateTimeInterface $updatedAt,
        public int $totalVentes = 0,
    ) {}

    public static function fromEntity(Complement $complement, int $totalVentes = 0): self
    {
        return new self(
            id: $complement->getId(),
            nom: $complement->getNom(),
            typeComplement: $complement->getTypeComplement(),
            prix: $complement->getPrix(),
            description: $complement->getDescription(),
            image: $complement->getImage(),
            isArchive: $complement->isArchive(),
            createdAt: $complement->getCreatedAt(),
            updatedAt: $complement->getUpdatedAt(),
            totalVentes: $totalVentes,
        );
    }

    /**
     * @param Complement[] $complements
     * @return self[]
     */
    public static function fromEntities(array $complements): array
    {
        return array_map(fn(Complement $complement) => self::fromEntity($complement), $complements);
    }
}
