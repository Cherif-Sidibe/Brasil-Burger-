<?php

namespace App\Service;

use App\DTO\ComplementDTO;

interface ComplementServiceInterface
{
    /**
     * @return array{complements: ComplementDTO[], currentPage: int, totalPages: int, totalItems: int, limit: int}
     */
    public function listerComplements(array $filters, int $page, int $limit): array;

    /**
     * @return array{complement: ComplementDTO, total_ventes: int}|null
     */
    public function obtenirComplementAvecStats(int $id): ?array;

    public function archiverComplement(int $id): void;

    public function restaurerComplement(int $id): void;
}
