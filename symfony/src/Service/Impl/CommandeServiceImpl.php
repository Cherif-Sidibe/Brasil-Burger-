<?php

namespace App\Service\Impl;

use App\DTO\CommandeDTO;
use App\DTO\DetailCommandeDTO;
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
        $commandes = $this->commandeRepository->findCommandesDuJour();
        return CommandeDTO::fromEntities($commandes);
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
        $commandes = $this->commandeRepository->findRecentes($limit);
        return CommandeDTO::fromEntities($commandes);
    }

    public function listerCommandes(array $filters, int $page, int $limit): array
    {
        $paginator = $this->commandeRepository->findWithFilters($filters, $page, $limit);

        $commandes = [];
        foreach ($paginator as $commande) {
            $commandes[] = CommandeDTO::fromEntity($commande);
        }

        return [
            'commandes' => $commandes,
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

        $detailsDTO = [];
        foreach ($detailsCommande as $detail) {
            $articleNom = null;
            $articleImage = null;

            switch ($detail->getTypeArticle()) {
                case 'BURGER':
                    $article = $burgerRepo->find($detail->getIdArticle());
                    if ($article) {
                        $articleNom = $article->getNom();
                        $articleImage = $article->getImage();
                    }
                    break;
                case 'MENU':
                    $article = $menuRepo->find($detail->getIdArticle());
                    if ($article) {
                        $articleNom = $article->getNom();
                        $articleImage = $article->getImage();
                    }
                    break;
                case 'COMPLEMENT':
                    $article = $complementRepo->find($detail->getIdArticle());
                    if ($article) {
                        $articleNom = $article->getNom();
                        $articleImage = $article->getImage();
                    }
                    break;
            }

            $detailsDTO[] = DetailCommandeDTO::fromEntity($detail, $articleNom, $articleImage);
        }

        return [
            'commande' => CommandeDTO::fromEntity($commande),
            'details' => $detailsDTO
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
