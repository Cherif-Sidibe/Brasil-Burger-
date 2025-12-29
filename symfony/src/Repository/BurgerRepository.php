<?php

namespace App\Repository;

use App\Entity\Burger;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

class BurgerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Burger::class);
    }

    public function findWithFilters(array $filters = [], int $page = 1, int $limit = 10): Paginator
    {
        $qb = $this->createQueryBuilder('b')
            ->leftJoin('App\Entity\DetailCommande', 'dc', 'WITH', 'dc.idArticle = b.id AND dc.typeArticle = :type')
            ->setParameter('type', 'BURGER')
            ->addSelect('COALESCE(SUM(dc.quantite), 0) as total_ventes')
            ->groupBy('b.id')
            ->orderBy('b.nom', 'ASC');

        if (isset($filters['statut'])) {
            if ($filters['statut'] === 'actif') {
                $qb->andWhere('b.isArchive = :archive')
                    ->setParameter('archive', false);
            } elseif ($filters['statut'] === 'archive') {
                $qb->andWhere('b.isArchive = :archive')
                    ->setParameter('archive', true);
            }
        }

        if (!empty($filters['recherche'])) {
            $qb->andWhere('b.nom LIKE :recherche OR b.description LIKE :recherche')
                ->setParameter('recherche', '%' . $filters['recherche'] . '%');
        }

        if (isset($filters['tri']) && $filters['tri'] === 'ventes') {
            $qb->orderBy('total_ventes', 'DESC');
        }

        $qb->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        return new Paginator($qb->getQuery(), true);
    }
}
