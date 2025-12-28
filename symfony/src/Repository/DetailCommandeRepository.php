<?php

namespace App\Repository;

use App\Entity\DetailCommande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DetailCommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DetailCommande::class);
    }

    public function findProduitsLesPlusVendus(int $limit = 10): array
    {
        $today = new \DateTime('today');
        $tomorrow = new \DateTime('tomorrow');

        return $this->createQueryBuilder('dc')
            ->select('
                dc.typeArticle as type_article,
                dc.idArticle as id_article,
                SUM(dc.quantite) as total_quantite,
                SUM(dc.prixUnitaire * dc.quantite) as chiffre_affaires
            ')
            ->join('dc.commande', 'c')
            ->where('c.dateCommande >= :today')
            ->andWhere('c.dateCommande < :tomorrow')
            ->andWhere('c.etatCommande != :statut')
            ->setParameter('today', $today)
            ->setParameter('tomorrow', $tomorrow)
            ->setParameter('statut', 'ANNULEE')
            ->groupBy('dc.typeArticle, dc.idArticle')
            ->orderBy('total_quantite', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
