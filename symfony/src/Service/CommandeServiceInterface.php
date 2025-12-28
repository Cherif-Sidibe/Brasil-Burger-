<?php

namespace App\Service;

interface CommandeServiceInterface
{
    public function getCommandesDuJour(): array;

    public function getCommandesValidees(): int;

    public function getCommandesAnnulees(): int;

    public function getRecettesDuJour(): float;

    public function getCommandesRecentes(int $limit = 10): array;
}
