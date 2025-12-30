<?php

namespace App\Service;

use App\DTO\CommandeDTO;

interface CommandeServiceInterface
{
    /**
     * @return CommandeDTO[]
     */
    public function getCommandesDuJour(): array;

    public function getCommandesValidees(): int;

    public function getCommandesAnnulees(): int;

    public function getRecettesDuJour(): float;

    /**
     * @return CommandeDTO[]
     */
    public function getCommandesRecentes(int $limit = 10): array;

    /**
     * @return array{commandes: CommandeDTO[], currentPage: int, totalPages: int, totalItems: int, limit: int}
     */
    public function listerCommandes(array $filters, int $page, int $limit): array;

    /**
     * @return array{commande: CommandeDTO, details: array}|null
     */
    public function obtenirCommandeAvecDetails(int $id): ?array;

    public function changerEtatCommande(int $id, string $nouvelEtat): void;

    public function assignerLivreur(int $id, int $idLivreur): void;
}
