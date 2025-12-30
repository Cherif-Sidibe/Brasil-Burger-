<?php

namespace App\Service\Impl;

use App\DTO\MenuDTO;
use App\Repository\MenuRepository;
use App\Service\MenuServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

class MenuServiceImpl implements MenuServiceInterface
{
    public function __construct(
        private MenuRepository $menuRepository,
        private EntityManagerInterface $entityManager
    ) {}

    public function listerMenus(array $filters, int $page, int $limit): array
    {
        $paginator = $this->menuRepository->findWithFilters($filters, $page, $limit);

        $menus = [];
        foreach ($paginator as $result) {
            $menu = $result[0];
            $totalVentes = (int) $result['total_ventes'];
            $menus[] = MenuDTO::fromEntity($menu, $totalVentes);
        }

        return [
            'menus' => $menus,
            'currentPage' => $page,
            'totalItems' => count($paginator),
            'totalPages' => ceil(count($paginator) / $limit),
            'limit' => $limit
        ];
    }

    public function obtenirMenuAvecStats(int $id): ?array
    {
        $qb = $this->entityManager->createQueryBuilder();

        $qb->select('m', 'COALESCE(SUM(dc.quantite), 0) as total_ventes')
            ->from('App\Entity\Menu', 'm')
            ->leftJoin('App\Entity\DetailCommande', 'dc', 'WITH', 'dc.idArticle = m.id AND dc.typeArticle = :type')
            ->leftJoin('m.burger', 'b')
            ->leftJoin('m.boisson', 'bo')
            ->leftJoin('m.frite', 'f')
            ->addSelect('b')
            ->addSelect('bo')
            ->addSelect('f')
            ->where('m.id = :id')
            ->setParameter('type', 'MENU')
            ->setParameter('id', $id)
            ->groupBy('m.id', 'b.id', 'bo.id', 'f.id');

        $result = $qb->getQuery()->getOneOrNullResult();

        if (!$result) {
            return null;
        }

        $menu = $result[0];
        $totalVentes = (int) $result['total_ventes'];

        return [
            'menu' => MenuDTO::fromEntity($menu, $totalVentes),
            'total_ventes' => $totalVentes
        ];
    }

    public function archiverMenu(int $id): void
    {
        $menu = $this->menuRepository->find($id);
        if ($menu) {
            $menu->setIsArchive(true);
            $this->entityManager->flush();
        }
    }

    public function restaurerMenu(int $id): void
    {
        $menu = $this->menuRepository->find($id);
        if ($menu) {
            $menu->setIsArchive(false);
            $this->entityManager->flush();
        }
    }
}
