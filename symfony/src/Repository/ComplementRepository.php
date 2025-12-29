<?php

namespace App\Repository;

use App\Entity\Complement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

class ComplementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Complement::class);
    }

    public function findWithFilters(array $filters, int $page, int $limit): Paginator
    {
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('App\Entity\DetailCommande', 'dc', 'WITH', 'dc.idArticle = c.id AND dc.typeArticle = :type')
            ->setParameter('type', 'COMPLEMENT')
            ->addSelect('COALESCE(SUM(dc.quantite), 0) as total_ventes')
            ->groupBy('c.id');

        // Filtre par statut
        if (isset($filters['statut']) && $filters['statut'] !== '') {
            if ($filters['statut'] === 'actif') {
                $qb->andWhere('c.isArchive = false');
            } elseif ($filters['statut'] === 'archive') {
                $qb->andWhere('c.isArchive = true');
            }
        }

        // Filtre par type
        if (isset($filters['type']) && $filters['type'] !== '') {
            $qb->andWhere('c.typeComplement = :typeComplement')
                ->setParameter('typeComplement', $filters['type']);
        }

        // Filtre par recherche
        if (isset($filters['recherche']) && $filters['recherche'] !== '') {
            $qb->andWhere('c.nom LIKE :recherche OR c.description LIKE :recherche')
                ->setParameter('recherche', '%' . $filters['recherche'] . '%');
        }

        // Tri
        if (isset($filters['tri']) && $filters['tri'] === 'ventes') {
            $qb->orderBy('total_ventes', 'DESC');
        } else {
            $qb->orderBy('c.nom', 'ASC');
        }

        $qb->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        return new Paginator($qb->getQuery());
    }
}
