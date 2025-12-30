<?php

namespace App\DTO;

use App\Entity\Commande;

final readonly class CommandeDTO
{
    public function __construct(
        public int $id,
        public UserDTO $client,
        public \DateTimeInterface $dateCommande,
        public string $montantTotal,
        public string $etatCommande,
        public string $typeLivraison,
        public ?string $adresseLivraison,
        public ?ZoneDTO $zone,
        public ?UserDTO $livreur,
    ) {}

    public static function fromEntity(Commande $commande): self
    {
        return new self(
            id: $commande->getId(),
            client: UserDTO::fromEntity($commande->getClient()),
            dateCommande: $commande->getDateCommande(),
            montantTotal: $commande->getMontantTotal(),
            etatCommande: $commande->getEtatCommande(),
            typeLivraison: $commande->getTypeLivraison(),
            adresseLivraison: $commande->getAdresseLivraison(),
            zone: $commande->getZone() ? ZoneDTO::fromEntity($commande->getZone()) : null,
            livreur: $commande->getLivreur() ? UserDTO::fromEntity($commande->getLivreur()) : null,
        );
    }

    /**
     * @param Commande[] $commandes
     * @return self[]
     */
    public static function fromEntities(array $commandes): array
    {
        return array_map(fn(Commande $commande) => self::fromEntity($commande), $commandes);
    }

    public function getEtatLabel(): string
    {
        return match ($this->etatCommande) {
            Commande::ETAT_EN_ATTENTE => 'En attente',
            Commande::ETAT_CONFIRMEE => 'Confirmée',
            Commande::ETAT_EN_PREPARATION => 'En préparation',
            Commande::ETAT_PRETE => 'Prête',
            Commande::ETAT_LIVREE => 'Livrée',
            Commande::ETAT_RETIREE => 'Retirée',
            Commande::ETAT_CONSOMMEE_SUR_PLACE => 'Consommée sur place',
            Commande::ETAT_ANNULEE => 'Annulée',
            default => $this->etatCommande,
        };
    }

    public function getTypeLivraisonLabel(): string
    {
        return match ($this->typeLivraison) {
            Commande::TYPE_SUR_PLACE => 'Sur place',
            Commande::TYPE_A_RECUPERER => 'À récupérer',
            Commande::TYPE_A_LIVRER => 'À livrer',
            default => $this->typeLivraison,
        };
    }
}
