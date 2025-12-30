<?php

namespace App\Controller\Twig;

use App\DTO\ZoneDTO;
use App\Entity\Zone;
use App\Repository\ZoneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/gestionnaire/zones')]
#[IsGranted('ROLE_GESTIONNAIRE')]
class ZoneController extends AbstractController
{
    public function __construct(
        private ZoneRepository $zoneRepository,
        private EntityManagerInterface $entityManager
    ) {}

    #[Route('', name: 'gestionnaire_zones_liste', methods: ['GET'])]
    public function liste(Request $request): Response
    {
        $statut = $request->query->get('statut', '');
        $page = max(1, $request->query->getInt('page', 1));
        $limit = 6;

        $qb = $this->zoneRepository->createQueryBuilder('z')
            ->orderBy('z.nom', 'ASC');

        if ($statut === 'actif') {
            $qb->andWhere('z.isArchive = false');
        } elseif ($statut === 'archive') {
            $qb->andWhere('z.isArchive = true');
        }

        // Compte total
        $countQb = clone $qb;
        $totalItems = count($countQb->getQuery()->getResult());
        $totalPages = max(1, ceil($totalItems / $limit));

        // Pagination
        $zonesEntities = $qb
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        $zones = ZoneDTO::fromEntities($zonesEntities);

        return $this->render('gestionnaire/zones/liste.html.twig', [
            'zones' => $zones,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalItems' => $totalItems,
            'statut' => $statut,
        ]);
    }

    #[Route('/archiver', name: 'gestionnaire_zones_archiver', methods: ['POST'])]
    public function archiver(Request $request): Response
    {
        $id = $request->query->getInt('id');
        $zone = $this->zoneRepository->find($id);

        if ($zone) {
            $zone->setIsArchive(true);
            $zone->setUpdatedAt(new \DateTime());
            $this->entityManager->flush();
            $this->addFlash('success', 'Zone archivée avec succès');
        } else {
            $this->addFlash('error', 'Zone introuvable');
        }

        return $this->redirectToRoute('gestionnaire_zones_liste');
    }

    #[Route('/restaurer', name: 'gestionnaire_zones_restaurer', methods: ['POST'])]
    public function restaurer(Request $request): Response
    {
        $id = $request->query->getInt('id');
        $zone = $this->zoneRepository->find($id);

        if ($zone) {
            $zone->setIsArchive(false);
            $zone->setUpdatedAt(new \DateTime());
            $this->entityManager->flush();
            $this->addFlash('success', 'Zone restaurée avec succès');
        } else {
            $this->addFlash('error', 'Zone introuvable');
        }

        return $this->redirectToRoute('gestionnaire_zones_liste', ['statut' => 'archive']);
    }
}
