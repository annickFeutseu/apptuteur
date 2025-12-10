<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Entity\Visite;
use App\Form\CompteRenduType;
use App\Form\VisiteType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;
use Dompdf\Dompdf;

final class VisiteController extends AbstractController
{
    public function visites(Etudiant $etudiant, Request $request, SessionInterface $session, EntityManagerInterface $em): Response
    {
        $tuteurId = $session->get('tuteur_id');
        if (!$tuteurId || $etudiant->getTuteur()->getId() !== $tuteurId) {
            $this->addFlash('error', 'Accès non autorisé.');
            return $this->redirectToRoute('dashboard');
        }

        // Récupérer filtre
        $statut = $request->query->get('statut', 'Toutes');
        $order = $request->query->get('order', 'ASC');

         // Construction du filtre
        $criteria = ['etudiant' => $etudiant];
        if ($statut !== 'Toutes') {
            $criteria['statut'] = $statut;
        }

        $visites = $em->getRepository(Visite::class)->findBy($criteria, ['date' => $order]);

        return $this->render('visite/list.html.twig', [
            'etudiant' => $etudiant,
            'visites' => $visites,
            'statut' => $statut,
            'order' => $order,
        ]);

    }

    // Ajouter une visite
    public function add(Etudiant $etudiant, Request $request, SessionInterface $session, EntityManagerInterface $em): Response
    {
        $tuteurId = $session->get('tuteur_id');
        if (!$tuteurId || $etudiant->getTuteur()->getId() !== $tuteurId) {
            $this->addFlash('error', 'Accès non autorisé.');
            return $this->redirectToRoute('dashboard');
        }
        
        $visite = new Visite();
        $visite->setEtudiant($etudiant);
        $visite->setTuteur($etudiant->getTuteur());
        $visite->setStatut(Visite::STATUT_PREVUE);
        // die($visite);

        $form = $this->createForm(VisiteType::class, $visite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($visite);
            $em->flush();

            $this->addFlash('success', 'Visite ajoutée avec succès.');
            return $this->redirectToRoute('etudiant_visites', ['id' => $etudiant->getId()]);
        }

        return $this->render('visite/form.html.twig', [
            'form' => $form->createView(),
            'mode' => 'add',
            'etudiant' => $etudiant,
            'visite' => $visite,
        ]);
    }

    public function edit(Visite $visite, Request $request, SessionInterface $session, EntityManagerInterface $em): Response
    {
        $tuteurId = $session->get('tuteur_id');
        if (!$tuteurId || $visite->getTuteur()->getId() !== $tuteurId) {
            $this->addFlash('error', 'Accès non autorisé.');
            return $this->redirectToRoute('dashboard');
        }

        $form = $this->createForm(VisiteType::class, $visite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Visite modifiée avec succès.');
            return $this->redirectToRoute('etudiant_visites', ['id' => $visite->getEtudiant()->getId()]);
        }

        return $this->render('visite/form.html.twig', [
            'form' => $form->createView(),
            'mode' => 'edit',
            'etudiant' => $visite->getEtudiant(),
            'visite' => $visite,
        ]);
    }

    public function compteRendu(Visite $visite, Request $request, SessionInterface $session, EntityManagerInterface $em): Response
    {
        $tuteurId = $session->get('tuteur_id');
        if (!$tuteurId || $visite->getTuteur()->getId() !== $tuteurId) {
            $this->addFlash('error', 'Accès non autorisé.');
            return $this->redirectToRoute('dashboard');
        }

        $form = $this->createForm(CompteRenduType::class, $visite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Compte-rendu enregistré avec succès.');
        }

        return $this->render('visite/compte_rendu.html.twig', [
            'visite' => $visite,
            'form' => $form->createView(),
        ]);
    }

    public function exportPdf(Visite $visite): Response
    {
        $html = $this->renderView('visite/compte_rendu_pdf.html.twig', ['visite' => $visite]);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $output = $dompdf->output();

        return new Response($output, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="compte_rendu_'.$visite->getId().'.pdf"',
        ]);
    }

    #[Route('/visite/{id}/change-statut', name: 'visite_change_statut', methods: ['POST'])]
    public function changeStatut(Visite $visite, Request $request, EntityManagerInterface $em): Response
    {
        $statut = $request->request->get('statut');

        if (!in_array($statut, Visite::STATUTS)) {
            $this->addFlash('error', 'Statut invalide');
            return $this->redirectToRoute('etudiant_visites', ['id' => $visite->getEtudiant()->getId()]);
        }

        $visite->setStatut($statut);
        $em->flush();

        $this->addFlash('success', 'Statut mis à jour');
        return $this->redirectToRoute('etudiant_visites', ['id' => $visite->getEtudiant()->getId()]);
    }

    #[Route('/visite/{id}/delete', name: 'visite_delete', methods: ['POST', 'DELETE'])]
    public function delete(Visite $visite, EntityManagerInterface $em): Response
    {
        $etudiantId = $visite->getEtudiant()->getId();

        $em->remove($visite);
        $em->flush();

        $this->addFlash('success', 'Visite supprimée avec succès.');
        return $this->redirectToRoute('etudiant_visites', ['id' => $etudiantId]);
    }


}
