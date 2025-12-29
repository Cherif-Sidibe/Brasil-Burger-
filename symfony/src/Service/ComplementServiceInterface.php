<?php

namespace App\Service;

interface ComplementServiceInterface
{
    public function listerComplements(array $filters, int $page, int $limit): array;

    public function obtenirComplementAvecStats(int $id): ?array;

    public function archiverComplement(int $id): void;

    public function restaurerComplement(int $id): void;
}
