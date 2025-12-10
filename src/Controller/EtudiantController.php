<?php

namespace App\Controller;

use App\Entity\Etudiant;
use App\Entity\Tuteur;
use App\Form\EtudiantType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class EtudiantController extends AbstractController
{
    public function add(Request $request, SessionInterface $session, EntityManagerInterface $em): Response {
        $tuteurId = $session->get('tuteur_id');
        if (!$tuteurId) {
            $this->addFlash('error', 'Vous devez être connecté.');
            return $this->redirectToRoute('app_login');
        }

        $etudiant = new Etudiant();

        // Récupérer le tuteur via le repository injecté
        $tuteur = $em->getRepository(Tuteur::class)->find($tuteurId);
        $etudiant->setTuteur($tuteur);

        $form = $this->createForm(EtudiantType::class, $etudiant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $em correspond à l'EntityManager injecté, c’est l’objet central de Doctrine qui gère tes entités.
            // persist() prépare l’entité à être sauvegardée en base de données. ne touche pas encore la base de données
            // flush() exécute les opérations en attente (comme les insertions, mises à jour, suppressions).
            $em->persist($etudiant); // Doctrine dit : "Ok, je vais gérer cet objet, je sais que je devrai l’insérer dans la DB."
            $em->flush();// Là, un INSERT INTO etudiant (nom, prenom) VALUES ('Dupont', 'Jean') est envoyé à la base de données.

            $this->addFlash('success', 'Étudiant ajouté avec succès.');
            return $this->redirectToRoute('dashboard');
        }

        return $this->render('etudiant/form.html.twig', [
            'form' => $form->createView(),
            'mode' => 'add',
        ]);
    }

    public function edit(Etudiant $etudiant, Request $request, SessionInterface $session, EntityManagerInterface $em
    ): Response {
        $tuteurId = $session->get('tuteur_id');
        if (!$tuteurId || $etudiant->getTuteur()->getId() !== $tuteurId) {
            $this->addFlash('error', 'Accès non autorisé.');
            return $this->redirectToRoute('dashboard');
        }

        $form = $this->createForm(EtudiantType::class, $etudiant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush(); // pas besoin de persist si l'entité existe déjà
            $this->addFlash('success', 'Étudiant modifié avec succès.');
            return $this->redirectToRoute('dashboard');
        }

        return $this->render('etudiant/form.html.twig', [
            'form' => $form->createView(),
            'etudiant' => $etudiant,
            'mode' => 'edit',
        ]);
    }

     #[Route('/student/{id}/delete', name: 'student_delete', methods: ['POST', 'DELETE'])]
    public function delete(Etudiant $etudiant, Request $request, SessionInterface $session, EntityManagerInterface $em): Response
    {
        $tuteurId = $session->get('tuteur_id');
        if (!$tuteurId || $etudiant->getTuteur()->getId() !== $tuteurId) {
            $this->addFlash('error', 'Accès non autorisé.');
            return $this->redirectToRoute('dashboard');
        }

        $em->remove($etudiant);
        $em->flush();
        $this->addFlash('success', 'Étudiant supprimé avec succès.');

        return $this->redirectToRoute('dashboard');
    }
}
