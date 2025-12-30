<?php

namespace App\Repository;

use App\Entity\Zone;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Zone>
 */
class ZoneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Zone::class);
    }

    /**
     * @return Zone[]
     */
    public function findAllOrderedByName(): array
    {
        return $this->createQueryBuilder('z')
            ->orderBy('z.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Zone[]
     */
    public function findActiveZones(): array
    {
        return $this->createQueryBuilder('z')
            ->where('z.isArchive = false')
            ->orderBy('z.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
