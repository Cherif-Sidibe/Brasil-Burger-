<?php

namespace App\Service;

use Doctrine\ORM\Tools\Pagination\Paginator;

interface BurgerServiceInterface
{
    public function listerBurgers(array $filters, int $page, int $limit): array;

    public function obtenirBurgerAvecStats(int $id): ?array;

    public function archiverBurger(int $id): void;

    public function restaurerBurger(int $id): void;
}
