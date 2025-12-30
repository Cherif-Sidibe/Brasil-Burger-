<?php

namespace App\DTO;

use App\Entity\Zone;

final readonly class ZoneDTO
{
    public function __construct(
        public int $id,
        public string $nom,
        public array $quartiers,
        public string $prixLivraison,
        public bool $isArchive,
        public \DateTimeInterface $createdAt,
        public \DateTimeInterface $updatedAt,
    ) {}

    public static function fromEntity(Zone $zone): self
    {
        return new self(
            id: $zone->getId(),
            nom: $zone->getNom(),
            quartiers: $zone->getQuartiers(),
            prixLivraison: $zone->getPrixLivraison(),
            isArchive: $zone->isArchive(),
            createdAt: $zone->getCreatedAt(),
            updatedAt: $zone->getUpdatedAt(),
        );
    }

    /**
     * @param Zone[] $zones
     * @return self[]
     */
    public static function fromEntities(array $zones): array
    {
        return array_map(fn(Zone $zone) => self::fromEntity($zone), $zones);
    }
}
