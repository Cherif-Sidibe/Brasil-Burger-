<?php

namespace App\DTO;

use App\Entity\Paiement;

final readonly class PaiementDTO
{
    public function __construct(
        public int $id,
        public int $commandeId,
        public \DateTimeInterface $datePaiement,
        public string $montant,
        public string $methodePaiement,
        public string $statutPaiement,
        public ?string $referenceTransaction,
    ) {}

    public static function fromEntity(Paiement $paiement): self
    {
        return new self(
            id: $paiement->getId(),
            commandeId: $paiement->getCommande()->getId(),
            datePaiement: $paiement->getDatePaiement(),
            montant: $paiement->getMontant(),
            methodePaiement: $paiement->getMethodePaiement(),
            statutPaiement: $paiement->getStatutPaiement(),
            referenceTransaction: $paiement->getReferenceTransaction(),
        );
    }

    /**
     * @param Paiement[] $paiements
     * @return self[]
     */
    public static function fromEntities(array $paiements): array
    {
        return array_map(fn(Paiement $paiement) => self::fromEntity($paiement), $paiements);
    }

    public function getMethodeLabel(): string
    {
        return match ($this->methodePaiement) {
            Paiement::METHODE_WAVE => 'Wave',
            Paiement::METHODE_ORANGE_MONEY => 'Orange Money',
            default => $this->methodePaiement,
        };
    }

    public function getStatutLabel(): string
    {
        return match ($this->statutPaiement) {
            Paiement::STATUT_EN_ATTENTE => 'En attente',
            Paiement::STATUT_REUSSI => 'Réussi',
            Paiement::STATUT_ECHEC => 'Échec',
            default => $this->statutPaiement,
        };
    }
}
