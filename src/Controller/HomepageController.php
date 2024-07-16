<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\VoitureRepository;
use Doctrine\ORM\EntityManagerInterface;

class HomepageController extends AbstractController
{
    private $voitureRepository;

    public function __construct(VoitureRepository $voitureRepository, EntityManagerInterface $entityManager)
    {
        $this->voitureRepository = $voitureRepository;
    }


    /**
     * Page d'accueil, listant les voitures
     */
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $voitures = $this->voitureRepository->findAll();

        return $this->render('accueil.html.twig', [
            'voitures' => $voitures,
        ]);
    }
}
