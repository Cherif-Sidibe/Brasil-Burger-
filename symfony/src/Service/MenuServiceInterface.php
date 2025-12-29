<?php

namespace App\Service;

interface MenuServiceInterface
{
    public function listerMenus(array $filters, int $page, int $limit): array;

    public function obtenirMenuAvecStats(int $id): ?array;

    public function archiverMenu(int $id): void;

    public function restaurerMenu(int $id): void;
}
