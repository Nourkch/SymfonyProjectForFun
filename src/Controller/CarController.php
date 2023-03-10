<?php

namespace App\Controller;
use App\Entity\Car;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CarRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class CarController extends AbstractController
{
    #[Route('/car', name: 'car')]
    public function index(): Response
    {
        return $this->render('car/index.html.twig', [
            'controller_name' => 'CarController',
        ]);
    }
// * two ways to retrieve data : ManagerRegistery 
// ! using ManagerRegistery service
// ! inject repository

    #[Route('/get_cars', name: 'get_cars')]
    public function fetch_cars(CarRepository $rep ): Response
    { 
        return $this->render('car/cars.html.twig', [
            'cars' => $rep->findAll(),
        ]);
    }

//? EntityManager em : insertion + suppression + modification
//? manager registery  gets the entity manager and EntityRepository

 #[Route('/add_car', name: 'add_car')]
    public function add_car(CarRepository $rep, ManagerRegistry $mr, Request $req): Response
    { 
        $car = new Car();
        $car->setName('BMW');

       // test if the car aleardy exists
        
     $VarName = $rep->findBy(['name'=>'BMW']);
     if($VarName==null)
{
    $em=$mr->getManager();
    $em->persist($car);
    $em->flush(); // used to insert, delete and update
}else 
    return new Response('BMW existe deja');


        return  $this->redirectToRoute('get_cars');

        // return $this->render('car/cars.html.twig', [
        //     'cars' => $rep->findAll(),
        // ]);
    }

    
    #[Route('/update_car/{id}', name: 'update_car')]
    public function update_car(ManagerRegistry $mr, $id ): Response
    { 
        $rep=$mr->getRepository(Car::class);
        $car = $rep->find($id);

        if($car==null)
       return  new Response("la voiture n'existe pas");
       else{
        $car->setName('new_name');
        $em=$mr->getManager();
        $em->flush();
        return $this->redirectToRoute('get_cars');
       }
    }

    #[Route('/delete_car/{id}', name: 'delete_car')]
    public function delete(ManagerRegistry $manager,$id): Response
    { 
        $repo = $manager->getRepository(Car::class);

       
    if($repo->find($id)==null)
        return new Response("la voiture n'existe pas.");
    else {

            $car= $repo->find($id);
            $em=$manager->getManager();
            $em->remove($car);
            $em->flush();

            return $this->redirectToRoute('get_cars');
    }  
    }


}
