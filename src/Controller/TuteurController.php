<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TuteurController extends AbstractController
{
    #[Route('/tuteurs', name: 'app_tuteurs')]
    public function index(Request $request): Response
    {
        $tuteurs = [
            [
                'id' => 1,
                'nom' => 'Johnson',
                'prenom' => 'Paul',
                'entreprise' => 'Acme',
                'email' => 'paul.johnson@acme.com',
                'telephone' => '06 00 00 00 01',
                'etudiants' => [
                    ['nom' => 'Martin', 'prenom' => 'Léa', 'sujet' => 'Détection d’anomalies sur flux bancaires'],
                    ['nom' => 'Durand', 'prenom' => 'Noah', 'sujet' => 'Dashboard risques crédit']
                ]
            ],
            [
                'id' => 2,
                'nom' => 'Walberg',
                'prenom' => 'Mark',
                'entreprise' => 'Globex',
                'email' => 'mark.walberg@globex.com',
                'telephone' => '06 00 00 00 02',
                'etudiants' => []
            ]
        ];

        // Lecture des paramètres de tri
        $sort = $request->query->get('sort', 'nom');
        $order = $request->query->get('order', 'asc');

        // Sécurisation (on n'autorise que nom et prenom)
        if (!in_array($sort, ['nom', 'prenom'])) {
            $sort = 'nom';
        }

        // Tri du tableau
        usort($tuteurs, function ($a, $b) use ($sort, $order) {
            return $order === 'asc'
                ? strcmp($a[$sort], $b[$sort])
                : strcmp($b[$sort], $a[$sort]);
        });

        return $this->render('tuteurs/index.html.twig', [
            'tuteurs' => $tuteurs
        ]);
    }

    public function show(int $id): Response
    {
        $tuteurs = [
            [
                'id' => 1,
                'nom' => 'Johnson',
                'prenom' => 'Paul',
                'entreprise' => 'Acme',
                'email' => 'paul.johnson@acme.com',
                'telephone' => '06 00 00 00 01',
                'etudiants' => [
                    ['nom' => 'Martin', 'prenom' => 'Léa', 'sujet' => 'Détection d’anomalies sur flux bancaires'],
                    ['nom' => 'Durand', 'prenom' => 'Noah', 'sujet' => 'Dashboard risques crédit']
                ]
            ],
            [
                'id' => 2,
                'nom' => 'Walberg',
                'prenom' => 'Mark',
                'entreprise' => 'Globex',
                'email' => 'mark.walberg@globex.com',
                'telephone' => '06 00 00 00 02',
                'etudiants' => []
            ]
        ];

        $tuteur = null;

        foreach ($tuteurs as $t) {
            if ($t['id'] == $id) {
                $tuteur = $t;
                break;
            }
        }

        if (!$tuteur) {
            throw $this->createNotFoundException("Tuteur non trouvé");
        }

        return $this->render('tuteurs/show.html.twig', [
            'tuteur' => $tuteur
        ]);
    }

    public function sujets(Request $request): Response
    {
        $tuteurs = [
            [
                'id' => 1,
                'nom' => 'Johnson',
                'prenom' => 'Paul',
                'entreprise' => 'Acme',
                'email' => 'paul.johnson@acme.com',
                'telephone' => '06 00 00 00 01',
                'etudiants' => [
                    ['nom' => 'Martin', 'prenom' => 'Léa', 'sujet' => 'Détection d’anomalies sur flux bancaires'],
                    ['nom' => 'Durand', 'prenom' => 'Noah', 'sujet' => 'Dashboard risques crédit']
                ]
            ],
            [
                'id' => 2,
                'nom' => 'Walberg',
                'prenom' => 'Mark',
                'entreprise' => 'Globex',
                'email' => 'mark.walberg@globex.com',
                'telephone' => '06 00 00 00 02',
                'etudiants' => []
            ]
        ];

        // Filtre éventuel par entreprise
        $entreprise = $request->query->get('entreprise');

        // Collecter tous les sujets
        $sujets = [];
        foreach ($tuteurs as $t) {
            foreach ($t['etudiants'] as $e) {
                if (!$entreprise || $entreprise == $t['entreprise']) {
                    $sujets[] = [
                        'tuteur' => $t['prenom'] . ' ' . $t['nom'],
                        'entreprise' => $t['entreprise'],
                        'etudiant' => $e['prenom'] . ' ' . $e['nom'],
                        'sujet' => $e['sujet'],
                    ];
                }
            }
        }

        return $this->render('sujet/index.html.twig', [
            'sujets' => $sujets,
            'entreprise' => $entreprise,
            'tuteurs' => $tuteurs
        ]);
    }


}
