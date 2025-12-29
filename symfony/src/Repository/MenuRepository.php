<?php

namespace App\Repository;

use App\Entity\Menu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

class MenuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Menu::class);
    }

    public function findWithFilters(array $filters, int $page, int $limit): Paginator
    {
        $qb = $this->createQueryBuilder('m')
            ->leftJoin('App\Entity\DetailCommande', 'dc', 'WITH', 'dc.idArticle = m.id AND dc.typeArticle = :type')
            ->leftJoin('m.burger', 'b')
            ->leftJoin('m.boisson', 'bo')
            ->leftJoin('m.frite', 'f')
            ->setParameter('type', 'MENU')
            ->addSelect('COALESCE(SUM(dc.quantite), 0) as total_ventes')
            ->addSelect('b')
            ->addSelect('bo')
            ->addSelect('f')
            ->groupBy('m.id', 'b.id', 'bo.id', 'f.id');

        // Filtre par statut
        if (isset($filters['statut']) && $filters['statut'] !== '') {
            if ($filters['statut'] === 'actif') {
                $qb->andWhere('m.isArchive = false');
            } elseif ($filters['statut'] === 'archive') {
                $qb->andWhere('m.isArchive = true');
            }
        }

        // Filtre par recherche
        if (isset($filters['recherche']) && $filters['recherche'] !== '') {
            $qb->andWhere('m.nom LIKE :recherche OR m.description LIKE :recherche')
                ->setParameter('recherche', '%' . $filters['recherche'] . '%');
        }

        // Tri
        if (isset($filters['tri']) && $filters['tri'] === 'ventes') {
            $qb->orderBy('total_ventes', 'DESC');
        } else {
            $qb->orderBy('m.nom', 'ASC');
        }

        $qb->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        return new Paginator($qb->getQuery());
    }
}
