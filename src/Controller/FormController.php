<?php

namespace App\Controller;

use Twig\Environment; // Integration de Twig, installation via composer require 'composer.phar require twig/twig' depuis le shell du docker
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FormController extends AbstractController
{
    // #[Route('/hello/{prenom}', name: 'app_hello')]
    public function hello(Environment $twig, string $prenom = "Bryan"): Response
    {
        $html = $twig->render('hello.html.twig', [
            'prenom' => $prenom
        ]);
        return new Response($html);
    }

    public function list(Environment $twig): Response
    {
        $tab = [
            'tuteurs' => [
                ['nom' => 'Doe', 'prenom' => 'John', 'age' => 20],
                ['nom' => 'Smith', 'prenom' => 'Jane', 'age' => 22],
                ['nom' => 'Brown', 'prenom' => 'Mike', 'age' => 19],
            ],
        ];

        return new Response($twig->render('tuteurs/list.html.twig', $tab));
    }

    // retorne le formulaire de recherche de tuteur
    public function search(Environment $twig): Response
    {
        return new Response($twig->render('tuteurs/search.html.twig'));
    }

    // traite le formulaire de recherche de tuteur
    public function verify(Environment $twig): Response
    {
        // Récupérer la query depuis la requête (GET)
        $query = $_POST['query'] ?? '';

        // Simuler une recherche de tuteurs
        $allTuteurs = [
            ['nom' => 'Doe', 'prenom' => 'John', 'age' => 20],
            ['nom' => 'Smith', 'prenom' => 'Jane', 'age' => 22],
            ['nom' => 'Brown', 'prenom' => 'Mike', 'age' => 19],
        ];

        $results = array_filter($allTuteurs, function ($tuteur) use ($query) {
            return stripos($tuteur['nom'], $query) !== false || stripos($tuteur['prenom'], $query) !== false;
        });

        return new Response($twig->render('tuteurs/search.html.twig', [
            'query' => $query,
            'results' => $results
        ]));
    }
}
