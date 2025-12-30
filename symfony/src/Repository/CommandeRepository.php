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

    public function findWithFilters(array $filters, int $page, int $limit): \Doctrine\ORM\Tools\Pagination\Paginator
    {
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('c.client', 'client')
            ->leftJoin('c.livreur', 'livreur')
            ->leftJoin('c.zone', 'zone')
            ->addSelect('client')
            ->addSelect('livreur')
            ->addSelect('zone');

        if (isset($filters['etat']) && $filters['etat'] !== '') {
            $qb->andWhere('c.etatCommande = :etat')
                ->setParameter('etat', $filters['etat']);
        }

        if (isset($filters['type']) && $filters['type'] !== '') {
            $qb->andWhere('c.typeLivraison = :type')
                ->setParameter('type', $filters['type']);
        }

        if (isset($filters['recherche']) && $filters['recherche'] !== '') {
            $qb->andWhere('client.nom LIKE :recherche OR client.prenom LIKE :recherche')
                ->setParameter('recherche', '%' . $filters['recherche'] . '%');
        }

        if (isset($filters['periode']) && $filters['periode'] !== '') {
            $today = new \DateTime('today');
            switch ($filters['periode']) {
                case 'aujourd_hui':
                    $tomorrow = new \DateTime('tomorrow');
                    $qb->andWhere('c.dateCommande >= :today')
                        ->andWhere('c.dateCommande < :tomorrow')
                        ->setParameter('today', $today)
                        ->setParameter('tomorrow', $tomorrow);
                    break;
                case 'cette_semaine':
                    $startWeek = (clone $today)->modify('monday this week');
                    $endWeek = (clone $startWeek)->modify('+7 days');
                    $qb->andWhere('c.dateCommande >= :startWeek')
                        ->andWhere('c.dateCommande < :endWeek')
                        ->setParameter('startWeek', $startWeek)
                        ->setParameter('endWeek', $endWeek);
                    break;
                case 'ce_mois':
                    $startMonth = (clone $today)->modify('first day of this month');
                    $endMonth = (clone $startMonth)->modify('first day of next month');
                    $qb->andWhere('c.dateCommande >= :startMonth')
                        ->andWhere('c.dateCommande < :endMonth')
                        ->setParameter('startMonth', $startMonth)
                        ->setParameter('endMonth', $endMonth);
                    break;
            }
        }

        if (isset($filters['tri']) && $filters['tri'] === 'montant') {
            $qb->orderBy('c.montantTotal', 'DESC');
        } else {
            $qb->orderBy('c.dateCommande', 'DESC');
        }

        $qb->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        return new \Doctrine\ORM\Tools\Pagination\Paginator($qb->getQuery());
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
