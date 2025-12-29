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

        $conn = $this->getEntityManager()->getConnection();

        $sql = "
            SELECT 
                dc.type_article,
                dc.id_article,
                SUM(dc.quantite) as total_quantite,
                SUM(dc.prix_unitaire * dc.quantite) as chiffre_affaires,
                CASE 
                    WHEN dc.type_article = 'BURGER' THEN b.nom
                    WHEN dc.type_article = 'MENU' THEN m.nom
                    WHEN dc.type_article = 'COMPLEMENT' THEN comp.nom
                END as nom_produit,
                CASE 
                    WHEN dc.type_article = 'BURGER' THEN b.image
                    WHEN dc.type_article = 'MENU' THEN m.image
                    WHEN dc.type_article = 'COMPLEMENT' THEN comp.image
                END as image_produit
            FROM detail_commande dc
            INNER JOIN commande c ON dc.id_commande = c.id
            LEFT JOIN burger b ON dc.type_article = 'BURGER' AND dc.id_article = b.id
            LEFT JOIN menu m ON dc.type_article = 'MENU' AND dc.id_article = m.id
            LEFT JOIN complement comp ON dc.type_article = 'COMPLEMENT' AND dc.id_article = comp.id
            WHERE c.date_commande >= ?
                AND c.date_commande < ?
                AND c.etat_commande != 'ANNULEE'
            GROUP BY dc.type_article, dc.id_article, nom_produit, image_produit
            ORDER BY total_quantite DESC
            LIMIT ?
        ";

        $result = $conn->executeQuery($sql, [
            $today->format('Y-m-d H:i:s'),
            $tomorrow->format('Y-m-d H:i:s'),
            $limit
        ]);

        return $result->fetchAllAssociative();
    }
}
