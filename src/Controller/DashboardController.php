<?php

namespace App\Controller;

use App\Repository\EtudiantRepository;
use App\Repository\TuteurRepository;
use App\Repository\VisiteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

final class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function index(
        SessionInterface $session,
        TuteurRepository $tuteurRepo,
        EtudiantRepository $etudiantRepo,
        VisiteRepository $visiteRepo
    ): Response {
        // Vérifier si tuteur connecté
        $tuteurId = $session->get('tuteur_id');
        if (!$tuteurId) {
            $this->addFlash('error', 'Vous devez être connecté pour accéder au dashboard.');
            return $this->redirectToRoute('app_login');
        }

        // Récupérer le tuteur connecté via repository injecté
        $tuteur = $tuteurRepo->find($tuteurId);
        if (!$tuteur) {
            $session->remove('tuteur_id');
            return $this->redirectToRoute('app_login');
        }

        // Étudiants du tuteur
        $etudiants = $etudiantRepo->findBy(['tuteur' => $tuteur]);

        // Prochaines visites (triées par date, seulement à venir)
        $prochainesVisites = $visiteRepo->createQueryBuilder('v')
            ->where('v.tuteur = :tuteur')
            ->andWhere('v.date >= :today')
            ->setParameter('tuteur', $tuteur)
            ->setParameter('today', new \DateTimeImmutable())
            ->orderBy('v.date', 'ASC')
            ->getQuery()
            ->getResult();

        return $this->render('dashboard/index.html.twig', [
            'tuteur' => $tuteur,
            'etudiants' => $etudiants,
            'prochainesVisites' => $prochainesVisites,
        ]);
    }
}
