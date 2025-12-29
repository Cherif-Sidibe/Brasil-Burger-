<?php

namespace App\Controller\Twig;

use App\Controller\BurgerControllerInterface;
use App\Service\BurgerServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/gestionnaire/burgers')]
#[IsGranted('ROLE_GESTIONNAIRE')]
class BurgerController extends AbstractController implements BurgerControllerInterface
{
    public function __construct(
        private BurgerServiceInterface $burgerService
    ) {}

    #[Route('', name: 'gestionnaire_burgers_liste', methods: ['GET'])]
    public function liste(Request $request): Response
    {
        $filters = [
            'statut' => $request->query->get('statut'),
            'recherche' => $request->query->get('recherche'),
            'tri' => $request->query->get('tri')
        ];

        $page = max(1, (int) $request->query->get('page', 1));
        $limit = 4;

        $result = $this->burgerService->listerBurgers($filters, $page, $limit);

        return $this->render('gestionnaire/burgers/liste.html.twig', [
            'burgers' => $result['burgers'],
            'currentPage' => $result['currentPage'],
            'totalPages' => $result['totalPages'],
            'totalItems' => $result['totalItems'],
            'filters' => $filters
        ]);
    }

    #[Route('/details', name: 'gestionnaire_burgers_details', methods: ['GET'])]
    public function details(Request $request): Response
    {
        $id = (int) $request->query->get('id');
        $result = $this->burgerService->obtenirBurgerAvecStats($id);

        if (!$result) {
            $this->addFlash('error', 'Burger introuvable');
            return $this->redirectToRoute('gestionnaire_burgers_liste');
        }

        return $this->render('gestionnaire/burgers/details.html.twig', [
            'burger' => $result['burger'],
            'total_ventes' => $result['total_ventes']
        ]);
    }

    #[Route('/archiver', name: 'gestionnaire_burgers_archiver', methods: ['POST'])]
    public function archiver(Request $request): Response
    {
        $id = (int) $request->query->get('id');
        $this->burgerService->archiverBurger($id);

        $this->addFlash('success', 'Burger archivé avec succès');

        return $this->redirectToRoute('gestionnaire_burgers_liste');
    }

    #[Route('/restaurer', name: 'gestionnaire_burgers_restaurer', methods: ['POST'])]
    public function restaurer(Request $request): Response
    {
        $id = (int) $request->query->get('id');
        $this->burgerService->restaurerBurger($id);

        $this->addFlash('success', 'Burger restauré avec succès');

        return $this->redirectToRoute('gestionnaire_burgers_liste');
    }
}
