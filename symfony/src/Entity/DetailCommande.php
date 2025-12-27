<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'detail_commande')]
class DetailCommande
{
    public const TYPE_BURGER = 'BURGER';
    public const TYPE_MENU = 'MENU';
    public const TYPE_COMPLEMENT = 'COMPLEMENT';

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Commande::class)]
    #[ORM\JoinColumn(name: 'id_commande', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private Commande $commande;

    #[ORM\Column(name: 'type_article', type: 'type_article_enum', nullable: false)]
    private string $typeArticle;

    #[ORM\Column(name: 'id_article', type: 'integer', nullable: false)]
    private int $idArticle;

    #[ORM\Column(type: 'integer', nullable: false)]
    private int $quantite = 1;

    #[ORM\Column(name: 'prix_unitaire', type: 'decimal', precision: 10, scale: 2, nullable: false)]
    private string $prixUnitaire;

    #[ORM\Column(name: 'sous_total', type: 'decimal', precision: 10, scale: 2, nullable: false)]
    private string $sousTotal;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCommande(): Commande
    {
        return $this->commande;
    }

    public function setCommande(Commande $commande): self
    {
        $this->commande = $commande;
        return $this;
    }

    public function getTypeArticle(): string
    {
        return $this->typeArticle;
    }

    public function setTypeArticle(string $typeArticle): self
    {
        $this->typeArticle = $typeArticle;
        return $this;
    }

    public function getIdArticle(): int
    {
        return $this->idArticle;
    }

    public function setIdArticle(int $idArticle): self
    {
        $this->idArticle = $idArticle;
        return $this;
    }

    public function getQuantite(): int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;
        return $this;
    }

    public function getPrixUnitaire(): string
    {
        return $this->prixUnitaire;
    }

    public function setPrixUnitaire(string $prixUnitaire): self
    {
        $this->prixUnitaire = $prixUnitaire;
        return $this;
    }

    public function getSousTotal(): string
    {
        return $this->sousTotal;
    }

    public function setSousTotal(string $sousTotal): self
    {
        $this->sousTotal = $sousTotal;
        return $this;
    }
}
