<?php

namespace App\Controller;
use App\Entity\Animal;
use App\Form\AnimalFormType;
use App\Repository\AnimalRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnimalController extends AbstractController
{
    #[Route('/animal', name: 'app_animal')]
    public function index(): Response
    {
        return $this->render('animal/index.html.twig', [
            'controller_name' => 'AnimalController',
        ]);
    }

    #[Route('/get_animals', name: 'get_animals')]
    public function getAll(ManagerRegistry $mr): Response
    {
        return $this->render('animal/animals.html.twig', [
            'animals' => $mr->getRepository(Animal::class)->findAll(),
        ]);
    }

    #[Route('/add_animal', name: 'add_animal')]
    public function add_animal(ManagerRegistry $mr, Request $req): Response
    {
        $repo = $mr->getRepository(Animal::class);

        $animal = new Animal();
        $form = $this->createForm(AnimalType::class,$animal);
        $form->handleRequest($req); // analyser la requete http
        
        if ($form->isSubmitted() && $form->isValid()) { 
            if($repo->find($animal.getId())==null)
            {
                $em=$mr->getManager();
                $em->persist($animal);
                $em->flush();
                return $this->redirectToRoute('get_animals');

            }else return new Response('Cet animal existe déjà');

        }
        return $this->render('animal/animals_form.html.twig' ,[
            'animals_form'=>$form->createView(),
        
                ]);

    }


}
