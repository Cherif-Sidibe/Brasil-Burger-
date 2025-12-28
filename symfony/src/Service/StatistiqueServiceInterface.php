<?php

namespace App\Service;

interface StatistiqueServiceInterface
{
    public function getProduitsLesPlusVendus(int $limit = 10): array;

    public function getStatistiquesJour(): array;
}
