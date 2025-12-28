<?php

namespace App\Controller\Twig;

use App\Controller\GestionnaireControllerInterface;
use App\Service\CommandeServiceInterface;
use App\Service\StatistiqueServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/gestionnaire')]
#[IsGranted('ROLE_GESTIONNAIRE')]
class GestionnaireController extends AbstractController implements GestionnaireControllerInterface
{
    public function __construct(
        private CommandeServiceInterface $commandeService,
        private StatistiqueServiceInterface $statistiqueService
    ) {}

    #[Route('/', name: 'gestionnaire_dashboard')]
    public function dashboard(): Response
    {
        $statistiques = $this->statistiqueService->getStatistiquesJour();
        $produitsVendus = $this->statistiqueService->getProduitsLesPlusVendus(3);
        $commandesRecentes = $this->commandeService->getCommandesRecentes(2);

        return $this->render('gestionnaire/dashboard.html.twig', [
            'user' => $this->getUser(),
            'statistiques' => $statistiques,
            'produitsVendus' => $produitsVendus,
            'commandesRecentes' => $commandesRecentes,
        ]);
    }
}
