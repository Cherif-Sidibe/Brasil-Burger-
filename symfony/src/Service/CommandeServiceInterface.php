<?php

namespace App\Service;

interface CommandeServiceInterface
{
    public function getCommandesDuJour(): array;

    public function getCommandesValidees(): int;

    public function getCommandesAnnulees(): int;

    public function getRecettesDuJour(): float;

    public function getCommandesRecentes(int $limit = 10): array;

    public function listerCommandes(array $filters, int $page, int $limit): array;

    public function obtenirCommandeAvecDetails(int $id): ?array;

    public function changerEtatCommande(int $id, string $nouvelEtat): void;

    public function assignerLivreur(int $id, int $idLivreur): void;
}
