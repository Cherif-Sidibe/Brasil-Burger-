<?php

namespace App\Service\Impl;

use App\DTO\ComplementDTO;
use App\Repository\ComplementRepository;
use App\Service\ComplementServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

class ComplementServiceImpl implements ComplementServiceInterface
{
    public function __construct(
        private ComplementRepository $complementRepository,
        private EntityManagerInterface $entityManager
    ) {}

    public function listerComplements(array $filters, int $page, int $limit): array
    {
        $paginator = $this->complementRepository->findWithFilters($filters, $page, $limit);

        $complements = [];
        foreach ($paginator as $result) {
            $complement = $result[0];
            $totalVentes = (int) $result['total_ventes'];
            $complements[] = ComplementDTO::fromEntity($complement, $totalVentes);
        }

        return [
            'complements' => $complements,
            'currentPage' => $page,
            'totalItems' => count($paginator),
            'totalPages' => ceil(count($paginator) / $limit),
            'limit' => $limit
        ];
    }

    public function obtenirComplementAvecStats(int $id): ?array
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select('c', 'COALESCE(SUM(dc.quantite), 0) as total_ventes')
            ->from('App\Entity\Complement', 'c')
            ->leftJoin('App\Entity\DetailCommande', 'dc', 'WITH', 'dc.idArticle = c.id AND dc.typeArticle = :type')
            ->where('c.id = :id')
            ->setParameter('type', 'COMPLEMENT')
            ->setParameter('id', $id)
            ->groupBy('c.id');

        $result = $qb->getQuery()->getOneOrNullResult();

        if (!$result) {
            return null;
        }

        $complement = $result[0];
        $totalVentes = (int) $result['total_ventes'];

        return [
            'complement' => ComplementDTO::fromEntity($complement, $totalVentes),
            'total_ventes' => $totalVentes
        ];
    }

    public function archiverComplement(int $id): void
    {
        $complement = $this->complementRepository->find($id);
        if ($complement) {
            $complement->setIsArchive(true);
            $this->entityManager->flush();
        }
    }

    public function restaurerComplement(int $id): void
    {
        $complement = $this->complementRepository->find($id);
        if ($complement) {
            $complement->setIsArchive(false);
            $this->entityManager->flush();
        }
    }
}
