<?php

namespace App\Service\Impl;

use App\DTO\BurgerDTO;
use App\Repository\BurgerRepository;
use App\Service\BurgerServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

class BurgerServiceImpl implements BurgerServiceInterface
{
    public function __construct(
        private BurgerRepository $burgerRepository,
        private EntityManagerInterface $entityManager
    ) {}

    public function listerBurgers(array $filters, int $page, int $limit): array
    {
        $paginator = $this->burgerRepository->findWithFilters($filters, $page, $limit);

        $burgers = [];
        foreach ($paginator as $result) {
            $burger = $result[0];
            $totalVentes = (int) $result['total_ventes'];
            $burgers[] = BurgerDTO::fromEntity($burger, $totalVentes);
        }

        return [
            'burgers' => $burgers,
            'currentPage' => $page,
            'totalItems' => count($paginator),
            'totalPages' => ceil(count($paginator) / $limit),
            'limit' => $limit
        ];
    }

    public function obtenirBurgerAvecStats(int $id): ?array
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select('b', 'COALESCE(SUM(dc.quantite), 0) as total_ventes')
            ->from('App\Entity\Burger', 'b')
            ->leftJoin('App\Entity\DetailCommande', 'dc', 'WITH', 'dc.idArticle = b.id AND dc.typeArticle = :type')
            ->where('b.id = :id')
            ->setParameter('type', 'BURGER')
            ->setParameter('id', $id)
            ->groupBy('b.id');

        $result = $qb->getQuery()->getOneOrNullResult();

        if (!$result) {
            return null;
        }

        $burger = $result[0];
        $totalVentes = (int) $result['total_ventes'];

        return [
            'burger' => BurgerDTO::fromEntity($burger, $totalVentes),
            'total_ventes' => $totalVentes
        ];
    }

    public function archiverBurger(int $id): void
    {
        $burger = $this->burgerRepository->find($id);
        if ($burger) {
            $burger->setIsArchive(true);
            $this->entityManager->flush();
        }
    }

    public function restaurerBurger(int $id): void
    {
        $burger = $this->burgerRepository->find($id);
        if ($burger) {
            $burger->setIsArchive(false);
            $this->entityManager->flush();
        }
    }
}
