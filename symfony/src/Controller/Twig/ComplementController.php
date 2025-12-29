<?php

namespace App\Controller\Twig;

use App\Controller\ComplementControllerInterface;
use App\Service\ComplementServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/gestionnaire/complements')]
#[IsGranted('ROLE_GESTIONNAIRE')]
class ComplementController extends AbstractController implements ComplementControllerInterface
{
    public function __construct(
        private ComplementServiceInterface $complementService
    ) {}

    #[Route('', name: 'gestionnaire_complements_liste', methods: ['GET'])]
    public function liste(Request $request): Response
    {
        $filters = [
            'statut' => $request->query->get('statut'),
            'type' => $request->query->get('type'),
            'recherche' => $request->query->get('recherche'),
            'tri' => $request->query->get('tri')
        ];

        $page = max(1, (int) $request->query->get('page', 1));
        $limit = 4;

        $result = $this->complementService->listerComplements($filters, $page, $limit);

        return $this->render('gestionnaire/complements/liste.html.twig', [
            'complements' => $result['complements'],
            'currentPage' => $result['currentPage'],
            'totalPages' => $result['totalPages'],
            'totalItems' => $result['totalItems'],
            'filters' => $filters
        ]);
    }

    #[Route('/details', name: 'gestionnaire_complements_details', methods: ['GET'])]
    public function details(Request $request): Response
    {
        $id = (int) $request->query->get('id');
        $result = $this->complementService->obtenirComplementAvecStats($id);

        if (!$result) {
            $this->addFlash('error', 'Complément introuvable');
            return $this->redirectToRoute('gestionnaire_complements_liste');
        }

        return $this->render('gestionnaire/complements/details.html.twig', [
            'complement' => $result['complement'],
            'total_ventes' => $result['total_ventes']
        ]);
    }

    #[Route('/archiver', name: 'gestionnaire_complements_archiver', methods: ['POST'])]
    public function archiver(Request $request): Response
    {
        $id = (int) $request->query->get('id');
        $this->complementService->archiverComplement($id);

        $this->addFlash('success', 'Complément archivé avec succès');

        return $this->redirectToRoute('gestionnaire_complements_liste');
    }

    #[Route('/restaurer', name: 'gestionnaire_complements_restaurer', methods: ['POST'])]
    public function restaurer(Request $request): Response
    {
        $id = (int) $request->query->get('id');
        $this->complementService->restaurerComplement($id);

        $this->addFlash('success', 'Complément restauré avec succès');

        return $this->redirectToRoute('gestionnaire_complements_liste');
    }
}
