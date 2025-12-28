<?php

namespace App\Service\Impl;

use App\Repository\DetailCommandeRepository;
use App\Service\CommandeServiceInterface;
use App\Service\StatistiqueServiceInterface;

class StatistiqueServiceImpl implements StatistiqueServiceInterface
{
    public function __construct(
        private DetailCommandeRepository $detailCommandeRepository,
        private CommandeServiceInterface $commandeService
    ) {}

    public function getProduitsLesPlusVendus(int $limit = 10): array
    {
        return $this->detailCommandeRepository->findProduitsLesPlusVendus($limit);
    }

    public function getStatistiquesJour(): array
    {
        return [
            'commandesDuJour' => count($this->commandeService->getCommandesDuJour()),
            'commandesValidees' => $this->commandeService->getCommandesValidees(),
            'recettesDuJour' => $this->commandeService->getRecettesDuJour(),
            'commandesAnnulees' => $this->commandeService->getCommandesAnnulees(),
        ];
    }
}
