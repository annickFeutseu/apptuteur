<?php 
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class MainController extends AbstractController {
    
    #[Route('/index')]
    public function index(): Response {
        return new Response(
            "<html><body><h1>Welcome to the Index Page</h1></body></html>"
        );
    }

    #[Route('/bonjour/{nom}', defaults:['nom' => 'Inconnu'], requirements: ['nom' => '[a-zA-Z]+'], methods: ['GET'])]
    public function indexbis(string $nom): Response {
        // $request = Request::createFromGlobals();
        // $nom = $request->query->get('nom', 'Inconnu');// Récupère le paramètre 'nom' de l'URL, valeur par défaut 'Inconnu'
        return new Response(
            "<html><body><h1>Bonjour $nom !</h1></body></html>"
        );
    }

    // a<100
    public function indexter(int $a, Request $request): Response {
        $b = $request->query->get('b', 5); // Récupère le paramètre 'b' de l'URL, valeur par défaut 0
        $somme = $a + (int)$b;
        return new Response(
            "<html><body><h1>La somme de $a et $b est égale à $somme</h1></body></html>"
        );
    }
}