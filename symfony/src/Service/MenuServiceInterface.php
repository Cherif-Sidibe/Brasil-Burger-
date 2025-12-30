<?php

namespace App\Service;

use App\DTO\MenuDTO;

interface MenuServiceInterface
{
    /**
     * @return array{menus: MenuDTO[], currentPage: int, totalPages: int, totalItems: int, limit: int}
     */
    public function listerMenus(array $filters, int $page, int $limit): array;

    /**
     * @return array{menu: MenuDTO, total_ventes: int}|null
     */
    public function obtenirMenuAvecStats(int $id): ?array;

    public function archiverMenu(int $id): void;

    public function restaurerMenu(int $id): void;
}
