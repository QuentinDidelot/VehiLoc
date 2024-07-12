<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\VoitureRepository;
use App\Entity\Voiture;
use Doctrine\ORM\EntityManagerInterface;

class VoituresController extends AbstractController
{

    public function __construct(
        private VoitureRepository $voitureRepository,
        private EntityManagerInterface $entityManager,
    )
    {

    }
    
    /**
     * Page d'accueil, listant les voitures
     */
    #[Route('/', name: 'app_home')]
    public function index(VoitureRepository $voitureRepository): Response
    {
        $voitures = $voitureRepository->findAll();

        return $this->render('accueil.html.twig', [
            'voitures' => $voitures,
        ]);
    }

    /**
     * Page de détail d'une voiture
     */
    #[Route('/voiture/{id}', name: 'app_car')]
    public function voiture(int $id): Response
    {

        $voiture = $this->voitureRepository->find($id);

        if(!$voiture) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('voiture.html.twig', [
            'voiture' => $voiture,
        ]);
    }

    /**
     * Suppression d'une voiture
     */
    #[Route('/voiture/{id}/supprimer', name: 'app_car_delete')]
    public function supprimerVoiture(int $id): Response
    {
        $voiture = $this->voitureRepository->find($id);

        if(!$voiture) {
            return $this->redirectToRoute('app_home');
        }

        $this->entityManager->remove($voiture);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_home');
    }

}
