<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Récupère tous les livreurs actifs (non archivés)
     * @return User[]
     */
    public function findLivreursActifs(): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.role = :role')
            ->andWhere('u.isArchive = false')
            ->setParameter('role', User::ROLE_LIVREUR)
            ->orderBy('u.nom', 'ASC')
            ->addOrderBy('u.prenom', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
