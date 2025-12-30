<?php

namespace App\Controller\Twig;

use App\Entity\Commande;
use App\Entity\User;
use App\Entity\Zone;
use App\Repository\CommandeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/gestionnaire/livreurs')]
#[IsGranted('ROLE_GESTIONNAIRE')]
class LivreurController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private CommandeRepository $commandeRepository,
        private EntityManagerInterface $entityManager
    ) {}

    #[Route('', name: 'gestionnaire_livreurs_liste', methods: ['GET'])]
    public function liste(Request $request): Response
    {
        // Récupérer tous les livreurs actifs
        $livreurs = $this->userRepository->createQueryBuilder('u')
            ->where('u.role = :role')
            ->andWhere('u.isArchive = false')
            ->setParameter('role', User::ROLE_LIVREUR)
            ->orderBy('u.nom', 'ASC')
            ->addOrderBy('u.prenom', 'ASC')
            ->getQuery()
            ->getResult();

        // Récupérer les commandes en attente de livreur (type A_LIVRER sans livreur assigné)
        $commandesEnAttente = $this->commandeRepository->createQueryBuilder('c')
            ->leftJoin('c.zone', 'z')
            ->addSelect('z')
            ->where('c.typeLivraison = :type')
            ->andWhere('c.livreur IS NULL')
            ->andWhere('c.etatCommande NOT IN (:etatsTermines)')
            ->setParameter('type', Commande::TYPE_A_LIVRER)
            ->setParameter('etatsTermines', [Commande::ETAT_LIVREE, Commande::ETAT_ANNULEE])
            ->orderBy('z.nom', 'ASC')
            ->addOrderBy('c.dateCommande', 'ASC')
            ->getQuery()
            ->getResult();

        // Grouper les commandes par zone
        $commandesParZone = [];
        foreach ($commandesEnAttente as $commande) {
            $zoneName = $commande->getZone() ? $commande->getZone()->getNom() : 'Sans zone';
            if (!isset($commandesParZone[$zoneName])) {
                $commandesParZone[$zoneName] = [];
            }
            $commandesParZone[$zoneName][] = $commande;
        }

        // Récupérer toutes les zones actives
        $zones = $this->entityManager->getRepository(Zone::class)->findBy(['isArchive' => false], ['nom' => 'ASC']);

        return $this->render('gestionnaire/livreurs/liste.html.twig', [
            'livreurs' => $livreurs,
            'commandesParZone' => $commandesParZone,
            'zones' => $zones
        ]);
    }

    #[Route('/archiver', name: 'gestionnaire_livreurs_archiver', methods: ['POST'])]
    public function archiver(Request $request): Response
    {
        $id = (int) $request->query->get('id');
        $livreur = $this->userRepository->find($id);

        if ($livreur && $livreur->getRole() === User::ROLE_LIVREUR) {
            $livreur->setIsArchive(true);
            $livreur->setUpdatedAt(new \DateTime());
            $this->entityManager->flush();
            $this->addFlash('success', 'Livreur archivé avec succès');
        } else {
            $this->addFlash('error', 'Livreur introuvable');
        }

        return $this->redirectToRoute('gestionnaire_livreurs_liste');
    }

    #[Route('/restaurer', name: 'gestionnaire_livreurs_restaurer', methods: ['POST'])]
    public function restaurer(Request $request): Response
    {
        $id = (int) $request->query->get('id');
        $livreur = $this->userRepository->find($id);

        if ($livreur && $livreur->getRole() === User::ROLE_LIVREUR) {
            $livreur->setIsArchive(false);
            $livreur->setUpdatedAt(new \DateTime());
            $this->entityManager->flush();
            $this->addFlash('success', 'Livreur restauré avec succès');
        } else {
            $this->addFlash('error', 'Livreur introuvable');
        }

        return $this->redirectToRoute('gestionnaire_livreurs_liste', ['statut' => 'archive']);
    }

    #[Route('/affecter', name: 'gestionnaire_livreurs_affecter', methods: ['POST'])]
    public function affecter(Request $request): Response
    {
        $affectations = $request->request->all('affectations');
        $count = 0;

        if (is_array($affectations)) {
            foreach ($affectations as $commandeId => $livreurId) {
                if (empty($livreurId)) {
                    continue;
                }

                $commande = $this->commandeRepository->find((int) $commandeId);
                $livreur = $this->userRepository->find((int) $livreurId);

                if ($commande && $livreur && $livreur->getRole() === User::ROLE_LIVREUR) {
                    $commande->setLivreur($livreur);
                    $count++;
                }
            }

            $this->entityManager->flush();
        }

        if ($count > 0) {
            $this->addFlash('success', $count . ' commande(s) affectée(s) avec succès');
        } else {
            $this->addFlash('info', 'Aucune affectation effectuée');
        }

        return $this->redirectToRoute('gestionnaire_livreurs_liste');
    }
}
