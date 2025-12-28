<?php

namespace App\Repository;

use App\Entity\Commande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    public function findCommandesDuJour(): array
    {
        $today = new \DateTime('today');
        $tomorrow = new \DateTime('tomorrow');

        return $this->createQueryBuilder('c')
            ->where('c.dateCommande >= :today')
            ->andWhere('c.dateCommande < :tomorrow')
            ->setParameter('today', $today)
            ->setParameter('tomorrow', $tomorrow)
            ->orderBy('c.dateCommande', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function countCommandesValideesDuJour(): int
    {
        $today = new \DateTime('today');
        $tomorrow = new \DateTime('tomorrow');

        return (int) $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.dateCommande >= :today')
            ->andWhere('c.dateCommande < :tomorrow')
            ->andWhere('c.etatCommande = :statut')
            ->setParameter('today', $today)
            ->setParameter('tomorrow', $tomorrow)
            ->setParameter('statut', 'CONFIRMEE')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function countCommandesAnnuleesDuJour(): int
    {
        $today = new \DateTime('today');
        $tomorrow = new \DateTime('tomorrow');

        return (int) $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->where('c.dateCommande >= :today')
            ->andWhere('c.dateCommande < :tomorrow')
            ->andWhere('c.etatCommande = :statut')
            ->setParameter('today', $today)
            ->setParameter('tomorrow', $tomorrow)
            ->setParameter('statut', 'ANNULEE')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function calculateRecettesDuJour(): float
    {
        $today = new \DateTime('today');
        $tomorrow = new \DateTime('tomorrow');

        $result = $this->createQueryBuilder('c')
            ->select('SUM(c.montantTotal)')
            ->where('c.dateCommande >= :today')
            ->andWhere('c.dateCommande < :tomorrow')
            ->andWhere('c.etatCommande != :statut')
            ->setParameter('today', $today)
            ->setParameter('tomorrow', $tomorrow)
            ->setParameter('statut', 'ANNULEE')
            ->getQuery()
            ->getSingleScalarResult();

        return (float) ($result ?? 0);
    }

    public function findRecentes(int $limit = 10): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.dateCommande', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
