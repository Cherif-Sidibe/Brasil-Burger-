<?php

namespace App\Service\Impl;

use App\Repository\CommandeRepository;
use App\Service\CommandeServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

class CommandeServiceImpl implements CommandeServiceInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CommandeRepository $commandeRepository
    ) {}

    public function getCommandesDuJour(): array
    {
        return $this->commandeRepository->findCommandesDuJour();
    }

    public function getCommandesValidees(): int
    {
        return $this->commandeRepository->countCommandesValideesDuJour();
    }

    public function getCommandesAnnulees(): int
    {
        return $this->commandeRepository->countCommandesAnnuleesDuJour();
    }

    public function getRecettesDuJour(): float
    {
        return $this->commandeRepository->calculateRecettesDuJour();
    }

    public function getCommandesRecentes(int $limit = 10): array
    {
        return $this->commandeRepository->findRecentes($limit);
    }
}
