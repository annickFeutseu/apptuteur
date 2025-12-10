<?php

namespace App\Controller;

use App\Repository\TuteurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

final class AuthController extends AbstractController
{
    public function login(Request $request, TuteurRepository $tuteurRepository, SessionInterface $session): Response
    {
        $error = null;

        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');

            $tuteur = $tuteurRepository->findOneBy(['email' => $email]);

            if ($tuteur) {
                // On stocke l'id du tuteur en session
                $session->set('tuteur_id', $tuteur->getId());

                return $this->redirectToRoute('app_dashboard');
            } else {
                $error = 'Email inconnu';
            }
        }

        return $this->render('auth/login.html.twig', [
            'error' => $error,
        ]);
    }
    public function logout(SessionInterface $session): Response
    {
        $session->remove('tuteur_id');
        $this->addFlash('success', 'Vous êtes bien déconnecté.');

        return $this->redirectToRoute('app_login');
    }
}
