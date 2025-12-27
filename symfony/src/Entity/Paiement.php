<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'paiement')]
class Paiement
{
    public const METHODE_WAVE = 'WAVE';
    public const METHODE_ORANGE_MONEY = 'ORANGE_MONEY';

    public const STATUT_EN_ATTENTE = 'EN_ATTENTE';
    public const STATUT_REUSSI = 'REUSSI';
    public const STATUT_ECHEC = 'ECHEC';

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\OneToOne(targetEntity: Commande::class)]
    #[ORM\JoinColumn(name: 'id_commande', referencedColumnName: 'id', nullable: false, unique: true, onDelete: 'RESTRICT')]
    private Commande $commande;

    #[ORM\Column(name: 'date_paiement', type: 'datetime', nullable: false)]
    private \DateTimeInterface $datePaiement;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: false)]
    private string $montant;

    #[ORM\Column(name: 'methode_paiement', type: 'methode_paiement_enum', nullable: false)]
    private string $methodePaiement;

    #[ORM\Column(name: 'statut_paiement', type: 'statut_paiement_enum', nullable: false)]
    private string $statutPaiement = self::STATUT_EN_ATTENTE;

    #[ORM\Column(name: 'reference_transaction', type: 'string', length: 100, nullable: true)]
    private ?string $referenceTransaction = null;

    public function __construct()
    {
        $this->datePaiement = new \DateTime();
    }

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

    public function getDatePaiement(): \DateTimeInterface
    {
        return $this->datePaiement;
    }

    public function setDatePaiement(\DateTimeInterface $datePaiement): self
    {
        $this->datePaiement = $datePaiement;
        return $this;
    }

    public function getMontant(): string
    {
        return $this->montant;
    }

    public function setMontant(string $montant): self
    {
        $this->montant = $montant;
        return $this;
    }

    public function getMethodePaiement(): string
    {
        return $this->methodePaiement;
    }

    public function setMethodePaiement(string $methodePaiement): self
    {
        $this->methodePaiement = $methodePaiement;
        return $this;
    }

    public function getStatutPaiement(): string
    {
        return $this->statutPaiement;
    }

    public function setStatutPaiement(string $statutPaiement): self
    {
        $this->statutPaiement = $statutPaiement;
        return $this;
    }

    public function getReferenceTransaction(): ?string
    {
        return $this->referenceTransaction;
    }

    public function setReferenceTransaction(?string $referenceTransaction): self
    {
        $this->referenceTransaction = $referenceTransaction;
        return $this;
    }
}
