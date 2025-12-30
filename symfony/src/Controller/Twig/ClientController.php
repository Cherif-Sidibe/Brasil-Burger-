<?php

namespace App\Controller\Twig;

use App\DTO\CommandeDTO;
use App\DTO\UserDTO;
use App\Entity\User;
use App\Repository\CommandeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/gestionnaire/clients')]
#[IsGranted('ROLE_GESTIONNAIRE')]
class ClientController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private CommandeRepository $commandeRepository,
        private EntityManagerInterface $entityManager
    ) {}

    #[Route('', name: 'gestionnaire_clients_liste', methods: ['GET'])]
    public function liste(Request $request): Response
    {
        $recherche = $request->query->get('recherche', '');
        $statut = $request->query->get('statut', '');
        $page = max(1, $request->query->getInt('page', 1));
        $limit = 4;

        $qb = $this->userRepository->createQueryBuilder('u')
            ->where('u.role = :role')
            ->setParameter('role', User::ROLE_CLIENT)
            ->orderBy('u.createdAt', 'DESC');

        if ($recherche) {
            $qb->andWhere('u.nom LIKE :recherche OR u.prenom LIKE :recherche OR u.email LIKE :recherche OR u.telephone LIKE :recherche')
                ->setParameter('recherche', '%' . $recherche . '%');
        }

        if ($statut === 'actif') {
            $qb->andWhere('u.isArchive = false');
        } elseif ($statut === 'archive') {
            $qb->andWhere('u.isArchive = true');
        }

        // Compte total
        $countQb = clone $qb;
        $totalItems = count($countQb->getQuery()->getResult());
        $totalPages = max(1, ceil($totalItems / $limit));

        // Pagination
        $clientsEntities = $qb
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        $clients = UserDTO::fromEntities($clientsEntities);

        // Récupérer la dernière commande pour chaque client
        $dernieresCommandes = [];
        foreach ($clientsEntities as $client) {
            $derniereCommande = $this->commandeRepository->createQueryBuilder('c')
                ->where('c.client = :client')
                ->setParameter('client', $client)
                ->orderBy('c.dateCommande', 'DESC')
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();
            $dernieresCommandes[$client->getId()] = $derniereCommande ? CommandeDTO::fromEntity($derniereCommande) : null;
        }

        return $this->render('gestionnaire/clients/liste.html.twig', [
            'clients' => $clients,
            'dernieresCommandes' => $dernieresCommandes,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'recherche' => $recherche,
            'statut' => $statut,
        ]);
    }

    #[Route('/archiver', name: 'gestionnaire_clients_archiver', methods: ['POST'])]
    public function archiver(Request $request): Response
    {
        $id = $request->query->getInt('id');
        $client = $this->userRepository->find($id);

        if ($client && $client->getRole() === User::ROLE_CLIENT) {
            $client->setIsArchive(true);
            $client->setUpdatedAt(new \DateTime());
            $this->entityManager->flush();
            $this->addFlash('success', 'Client archivé avec succès');
        } else {
            $this->addFlash('error', 'Client introuvable');
        }

        return $this->redirectToRoute('gestionnaire_clients_liste');
    }

    #[Route('/restaurer', name: 'gestionnaire_clients_restaurer', methods: ['POST'])]
    public function restaurer(Request $request): Response
    {
        $id = $request->query->getInt('id');
        $client = $this->userRepository->find($id);

        if ($client && $client->getRole() === User::ROLE_CLIENT) {
            $client->setIsArchive(false);
            $client->setUpdatedAt(new \DateTime());
            $this->entityManager->flush();
            $this->addFlash('success', 'Client restauré avec succès');
        } else {
            $this->addFlash('error', 'Client introuvable');
        }

        return $this->redirectToRoute('gestionnaire_clients_liste', ['statut' => 'archive']);
    }
}
