<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\VoitureRepository;
use App\Entity\Voiture;
use App\Form\VoitureType;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/voiture', name: 'app_car')]

class VoituresController extends AbstractController
{
    private $voitureRepository;
    private $entityManager;

    public function __construct(VoitureRepository $voitureRepository, EntityManagerInterface $entityManager)
    {
        $this->voitureRepository = $voitureRepository;
        $this->entityManager = $entityManager;
    }


    /**
     * Page de détail d'une voiture
     */
    #[Route('/{id<\d+>}', name:'') ]
    public function voiture(int $id): Response
    {
        $voiture = $this->voitureRepository->find($id);

        if (!$voiture) {
            return $this->redirectToRoute('app_home');
        }

        return $this->render('voiture.html.twig', [
            'voiture' => $voiture,
        ]);
    }

    /**
     * Formulaire d'ajout d'une voiture
     */
    #[Route('/ajouter', name: '_add')]
    public function ajouterVoiture(Request $request): Response
    {
        $voiture = new Voiture();

        $form = $this->createForm(VoitureType::class, $voiture);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($voiture);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_car', ['id' => $voiture->getId()]);
        }

        return $this->render('ajouterVoiture.html.twig', [
            'form' => $form->createView(), 
        ]);
    }

    /**
     * Suppression d'une voiture
     */
    #[Route('/{id<\d+>}/supprimer', name: '_delete')]
    public function supprimerVoiture(int $id): Response
    {
        $voiture = $this->voitureRepository->find($id);

        if (!$voiture) {
            return $this->redirectToRoute('app_home');
        }

        $this->entityManager->remove($voiture);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_home');
    }
}
