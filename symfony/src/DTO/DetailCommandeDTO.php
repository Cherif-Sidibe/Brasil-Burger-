<?php

namespace App\DTO;

use App\Entity\DetailCommande;

final readonly class DetailCommandeDTO
{
    public function __construct(
        public int $id,
        public int $commandeId,
        public string $typeArticle,
        public int $idArticle,
        public int $quantite,
        public string $prixUnitaire,
        public string $sousTotal,
        public ?string $articleNom = null,
        public ?string $articleImage = null,
    ) {}

    public static function fromEntity(DetailCommande $detail, ?string $articleNom = null, ?string $articleImage = null): self
    {
        return new self(
            id: $detail->getId(),
            commandeId: $detail->getCommande()->getId(),
            typeArticle: $detail->getTypeArticle(),
            idArticle: $detail->getIdArticle(),
            quantite: $detail->getQuantite(),
            prixUnitaire: $detail->getPrixUnitaire(),
            sousTotal: $detail->getSousTotal(),
            articleNom: $articleNom,
            articleImage: $articleImage,
        );
    }

    /**
     * @param DetailCommande[] $details
     * @return self[]
     */
    public static function fromEntities(array $details): array
    {
        return array_map(fn(DetailCommande $detail) => self::fromEntity($detail), $details);
    }

    public function getTypeArticleLabel(): string
    {
        return match ($this->typeArticle) {
            DetailCommande::TYPE_BURGER => 'Burger',
            DetailCommande::TYPE_MENU => 'Menu',
            DetailCommande::TYPE_COMPLEMENT => 'Complément',
            default => $this->typeArticle,
        };
    }
}
