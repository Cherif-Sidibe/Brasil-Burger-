<?php

namespace App\Service\Impl;

use App\Repository\CommandeRepository;
use App\Service\CommandeServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

class CommandeServiceImpl implements CommandeServiceInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CommandeRepository $commandeRepository
    ) {}

    public function getCommandesDuJour(): array
    {
        return $this->commandeRepository->findCommandesDuJour();
    }

    public function getCommandesValidees(): int
    {
        return $this->commandeRepository->countCommandesValideesDuJour();
    }

    public function getCommandesAnnulees(): int
    {
        return $this->commandeRepository->countCommandesAnnuleesDuJour();
    }

    public function getRecettesDuJour(): float
    {
        return $this->commandeRepository->calculateRecettesDuJour();
    }

    public function getCommandesRecentes(int $limit = 10): array
    {
        return $this->commandeRepository->findRecentes($limit);
    }

    public function listerCommandes(array $filters, int $page, int $limit): array
    {
        $paginator = $this->commandeRepository->findWithFilters($filters, $page, $limit);

        return [
            'commandes' => iterator_to_array($paginator),
            'currentPage' => $page,
            'totalItems' => count($paginator),
            'totalPages' => ceil(count($paginator) / $limit),
            'limit' => $limit
        ];
    }

    public function obtenirCommandeAvecDetails(int $id): ?array
    {
        $commande = $this->commandeRepository->find($id);

        if (!$commande) {
            return null;
        }

        $detailsCommande = $this->entityManager->createQueryBuilder()
            ->select('dc')
            ->from('App\Entity\DetailCommande', 'dc')
            ->where('dc.commande = :commande')
            ->setParameter('commande', $commande)
            ->getQuery()
            ->getResult();

        $burgerRepo = $this->entityManager->getRepository('App\Entity\Burger');
        $menuRepo = $this->entityManager->getRepository('App\Entity\Menu');
        $complementRepo = $this->entityManager->getRepository('App\Entity\Complement');

        $detailsAvecArticles = [];
        foreach ($detailsCommande as $detail) {
            $detailData = [
                'detail' => $detail,
                'article' => null
            ];

            switch ($detail->getTypeArticle()) {
                case 'BURGER':
                    $detailData['article'] = $burgerRepo->find($detail->getIdArticle());
                    break;
                case 'MENU':
                    $detailData['article'] = $menuRepo->find($detail->getIdArticle());
                    break;
                case 'COMPLEMENT':
                    $detailData['article'] = $complementRepo->find($detail->getIdArticle());
                    break;
            }

            $detailsAvecArticles[] = $detailData;
        }

        return [
            'commande' => $commande,
            'details' => $detailsAvecArticles
        ];
    }

    public function changerEtatCommande(int $id, string $nouvelEtat): void
    {
        $commande = $this->commandeRepository->find($id);
        if ($commande) {
            $commande->setEtatCommande($nouvelEtat);
            $this->entityManager->flush();
        }
    }

    public function assignerLivreur(int $id, int $idLivreur): void
    {
        $commande = $this->commandeRepository->find($id);
        $livreur = $this->entityManager->getRepository('App\Entity\User')->find($idLivreur);

        if ($commande && $livreur) {
            $commande->setLivreur($livreur);
            $this->entityManager->flush();
        }
    }
}
