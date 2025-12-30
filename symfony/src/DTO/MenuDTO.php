<?php

namespace App\DTO;

use App\Entity\Menu;

final readonly class MenuDTO
{
    public function __construct(
        public int $id,
        public string $nom,
        public string $prix,
        public ?string $description,
        public ?string $image,
        public BurgerDTO $burger,
        public ComplementDTO $boisson,
        public ComplementDTO $frite,
        public bool $isArchive,
        public \DateTimeInterface $createdAt,
        public \DateTimeInterface $updatedAt,
        public int $totalVentes = 0,
    ) {}

    public static function fromEntity(Menu $menu, int $totalVentes = 0): self
    {
        return new self(
            id: $menu->getId(),
            nom: $menu->getNom(),
            prix: $menu->getPrix(),
            description: $menu->getDescription(),
            image: $menu->getImage(),
            burger: BurgerDTO::fromEntity($menu->getBurger()),
            boisson: ComplementDTO::fromEntity($menu->getBoisson()),
            frite: ComplementDTO::fromEntity($menu->getFrite()),
            isArchive: $menu->isArchive(),
            createdAt: $menu->getCreatedAt(),
            updatedAt: $menu->getUpdatedAt(),
            totalVentes: $totalVentes,
        );
    }

    /**
     * @param Menu[] $menus
     * @return self[]
     */
    public static function fromEntities(array $menus): array
    {
        return array_map(fn(Menu $menu) => self::fromEntity($menu), $menus);
    }
}
