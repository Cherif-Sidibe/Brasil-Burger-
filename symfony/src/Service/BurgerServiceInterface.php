<?php

namespace App\Service;

use App\DTO\BurgerDTO;

interface BurgerServiceInterface
{
    /**
     * @return array{burgers: BurgerDTO[], currentPage: int, totalPages: int, totalItems: int, limit: int}
     */
    public function listerBurgers(array $filters, int $page, int $limit): array;

    /**
     * @return array{burger: BurgerDTO, total_ventes: int}|null
     */
    public function obtenirBurgerAvecStats(int $id): ?array;

    public function archiverBurger(int $id): void;

    public function restaurerBurger(int $id): void;
}
