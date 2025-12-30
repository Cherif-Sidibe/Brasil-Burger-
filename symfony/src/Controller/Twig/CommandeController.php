<?php

namespace App\Controller\Twig;

use App\Controller\CommandeControllerInterface;
use App\DTO\UserDTO;
use App\Repository\UserRepository;
use App\Service\CommandeServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/gestionnaire/commandes')]
#[IsGranted('ROLE_GESTIONNAIRE')]
class CommandeController extends AbstractController implements CommandeControllerInterface
{
    public function __construct(
        private CommandeServiceInterface $commandeService,
        private UserRepository $userRepository
    ) {}

    #[Route('', name: 'gestionnaire_commandes_liste', methods: ['GET'])]
    public function liste(Request $request): Response
    {
        $filters = [
            'etat' => $request->query->get('etat'),
            'type' => $request->query->get('type'),
            'recherche' => $request->query->get('recherche'),
            'periode' => $request->query->get('periode'),
            'tri' => $request->query->get('tri')
        ];

        $page = max(1, (int) $request->query->get('page', 1));
        $limit = 4;

        $result = $this->commandeService->listerCommandes($filters, $page, $limit);

        return $this->render('gestionnaire/commandes/liste.html.twig', [
            'commandes' => $result['commandes'],
            'currentPage' => $result['currentPage'],
            'totalPages' => $result['totalPages'],
            'totalItems' => $result['totalItems'],
            'filters' => $filters
        ]);
    }

    #[Route('/details', name: 'gestionnaire_commandes_details', methods: ['GET'])]
    public function details(Request $request): Response
    {
        $id = (int) $request->query->get('id');
        $result = $this->commandeService->obtenirCommandeAvecDetails($id);

        if (!$result) {
            $this->addFlash('error', 'Commande introuvable');
            return $this->redirectToRoute('gestionnaire_commandes_liste');
        }

        // Récupérer les livreurs disponibles pour l'assignation
        $livreursEntities = $this->userRepository->findLivreursActifs();
        $livreurs = UserDTO::fromEntities($livreursEntities);

        return $this->render('gestionnaire/commandes/details.html.twig', [
            'commande' => $result['commande'],
            'details' => $result['details'],
            'livreurs' => $livreurs
        ]);
    }

    #[Route('/changer-etat', name: 'gestionnaire_commandes_changer_etat', methods: ['POST'])]
    public function changerEtat(Request $request): Response
    {
        $id = (int) $request->query->get('id');
        $nouvelEtat = $request->request->get('etat');

        $this->commandeService->changerEtatCommande($id, $nouvelEtat);

        $this->addFlash('success', 'État de la commande modifié avec succès');

        return $this->redirectToRoute('gestionnaire_commandes_details', ['id' => $id]);
    }

    #[Route('/assigner-livreur', name: 'gestionnaire_commandes_assigner_livreur', methods: ['POST'])]
    public function assignerLivreur(Request $request): Response
    {
        $id = (int) $request->query->get('id');
        $idLivreur = (int) $request->request->get('id_livreur');

        $this->commandeService->assignerLivreur($id, $idLivreur);

        $this->addFlash('success', 'Livreur assigné avec succès');

        return $this->redirectToRoute('gestionnaire_commandes_details', ['id' => $id]);
    }
}
