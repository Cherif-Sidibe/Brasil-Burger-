<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'commande')]
class Commande
{
    public const ETAT_EN_ATTENTE = 'EN_ATTENTE';
    public const ETAT_CONFIRMEE = 'CONFIRMEE';
    public const ETAT_EN_PREPARATION = 'EN_PREPARATION';
    public const ETAT_PRETE = 'PRETE';
    public const ETAT_LIVREE = 'LIVREE';
    public const ETAT_RETIREE = 'RETIREE';
    public const ETAT_CONSOMMEE_SUR_PLACE = 'CONSOMMEE_SUR_PLACE';
    public const ETAT_ANNULEE = 'ANNULEE';

    public const TYPE_SUR_PLACE = 'SUR_PLACE';
    public const TYPE_A_RECUPERER = 'A_RECUPERER';
    public const TYPE_A_LIVRER = 'A_LIVRER';

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'id_client', referencedColumnName: 'id', nullable: false, onDelete: 'RESTRICT')]
    private User $client;

    #[ORM\Column(name: 'date_commande', type: 'datetime', nullable: false)]
    private \DateTimeInterface $dateCommande;

    #[ORM\Column(name: 'montant_total', type: 'decimal', precision: 10, scale: 2, nullable: false)]
    private string $montantTotal;

    #[ORM\Column(name: 'etat_commande', type: 'etat_commande_enum', nullable: false)]
    private string $etatCommande = self::ETAT_EN_ATTENTE;

    #[ORM\Column(name: 'type_livraison', type: 'type_livraison_enum', nullable: false)]
    private string $typeLivraison;

    #[ORM\Column(name: 'adresse_livraison', type: 'text', nullable: true)]
    private ?string $adresseLivraison = null;

    #[ORM\ManyToOne(targetEntity: Zone::class)]
    #[ORM\JoinColumn(name: 'id_zone', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?Zone $zone = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'id_livreur', referencedColumnName: 'id', nullable: true, onDelete: 'SET NULL')]
    private ?User $livreur = null;

    public function __construct()
    {
        $this->dateCommande = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClient(): User
    {
        return $this->client;
    }

    public function setClient(User $client): self
    {
        $this->client = $client;
        return $this;
    }

    public function getDateCommande(): \DateTimeInterface
    {
        return $this->dateCommande;
    }

    public function setDateCommande(\DateTimeInterface $dateCommande): self
    {
        $this->dateCommande = $dateCommande;
        return $this;
    }

    public function getMontantTotal(): string
    {
        return $this->montantTotal;
    }

    public function setMontantTotal(string $montantTotal): self
    {
        $this->montantTotal = $montantTotal;
        return $this;
    }

    public function getEtatCommande(): string
    {
        return $this->etatCommande;
    }

    public function setEtatCommande(string $etatCommande): self
    {
        $this->etatCommande = $etatCommande;
        return $this;
    }

    public function getTypeLivraison(): string
    {
        return $this->typeLivraison;
    }

    public function setTypeLivraison(string $typeLivraison): self
    {
        $this->typeLivraison = $typeLivraison;
        return $this;
    }

    public function getAdresseLivraison(): ?string
    {
        return $this->adresseLivraison;
    }

    public function setAdresseLivraison(?string $adresseLivraison): self
    {
        $this->adresseLivraison = $adresseLivraison;
        return $this;
    }

    public function getZone(): ?Zone
    {
        return $this->zone;
    }

    public function setZone(?Zone $zone): self
    {
        $this->zone = $zone;
        return $this;
    }

    public function getLivreur(): ?User
    {
        return $this->livreur;
    }

    public function setLivreur(?User $livreur): self
    {
        $this->livreur = $livreur;
        return $this;
    }
}
