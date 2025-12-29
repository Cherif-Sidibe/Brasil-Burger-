<?php

namespace App\Controller\Twig;

use App\Controller\MenuControllerInterface;
use App\Service\MenuServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/gestionnaire/menus')]
#[IsGranted('ROLE_GESTIONNAIRE')]
class MenuController extends AbstractController implements MenuControllerInterface
{
    public function __construct(
        private MenuServiceInterface $menuService
    ) {}

    #[Route('', name: 'gestionnaire_menus_liste', methods: ['GET'])]
    public function liste(Request $request): Response
    {
        $filters = [
            'statut' => $request->query->get('statut'),
            'recherche' => $request->query->get('recherche'),
            'tri' => $request->query->get('tri')
        ];

        $page = max(1, (int) $request->query->get('page', 1));
        $limit = 4;

        $result = $this->menuService->listerMenus($filters, $page, $limit);

        return $this->render('gestionnaire/menus/liste.html.twig', [
            'menus' => $result['menus'],
            'currentPage' => $result['currentPage'],
            'totalPages' => $result['totalPages'],
            'totalItems' => $result['totalItems'],
            'filters' => $filters
        ]);
    }

    #[Route('/details', name: 'gestionnaire_menus_details', methods: ['GET'])]
    public function details(Request $request): Response
    {
        $id = (int) $request->query->get('id');
        $result = $this->menuService->obtenirMenuAvecStats($id);

        if (!$result) {
            $this->addFlash('error', 'Menu introuvable');
            return $this->redirectToRoute('gestionnaire_menus_liste');
        }

        return $this->render('gestionnaire/menus/details.html.twig', [
            'menu' => $result['menu'],
            'total_ventes' => $result['total_ventes']
        ]);
    }

    #[Route('/archiver', name: 'gestionnaire_menus_archiver', methods: ['POST'])]
    public function archiver(Request $request): Response
    {
        $id = (int) $request->query->get('id');
        $this->menuService->archiverMenu($id);

        $this->addFlash('success', 'Menu archivé avec succès');

        return $this->redirectToRoute('gestionnaire_menus_liste');
    }

    #[Route('/restaurer', name: 'gestionnaire_menus_restaurer', methods: ['POST'])]
    public function restaurer(Request $request): Response
    {
        $id = (int) $request->query->get('id');
        $this->menuService->restaurerMenu($id);

        $this->addFlash('success', 'Menu restauré avec succès');

        return $this->redirectToRoute('gestionnaire_menus_liste');
    }
}
