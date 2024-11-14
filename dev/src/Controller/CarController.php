<?php

namespace App\Controller;

use App\Entity\Vehicule;
use App\Repository\VehiculeRepository;
use App\Repository\VehiculeRepositoryInterface;
use App\Form\VehiculesFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

// ...

class CarController extends AbstractController
{
    public function __construct(
        #[Autowire(service: VehiculeRepository::class)]
        private VehiculeRepositoryInterface $vehiculeRepository
    ) {
    }

    #[Route(path: '/vehicules', name: 'list_vehicules')]
    #[IsGranted('ROLE_USER')]
    public function showAll(): Response
    {
        $vehicules = $this->vehiculeRepository->findAll();

        return $this->render('vehicule/list.html.twig', ['vehicules' => $vehicules]);
    }

    #[Route(path: '/vehiculeBy/{name}', name: 'showBy_vehicule')]
    #[IsGranted('ROLE_USER')]
    public function showBy(): String
    {
        return "coucou les gars";
    }

    #[Route(path: '/vehicule/{id}', name: 'show_vehicule')]
    #[IsGranted('ROLE_USER')]
    public function show(int $id): Response
    {
        $vehicule = $this->vehiculeRepository->find($id);

        if (!$vehicule) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }

        return $this->render('vehicule/list.html.twig', ['vehicules' => $vehicule]);
    }

    #[Route(path: 'vehicules/ajout', name: 'add_vehicule')]
    #[IsGranted('ROLE_EDITOR')]
    public function add(Request $request, EntityManagerInterface $em, SluggerInterface $slugger, Security $security): Response
    {

        //Create a new vehicule
        $vehicule = new Vehicule();

        //Create the form
        $vehiculeForm = $this->createForm(VehiculesFormType::class, $vehicule);

        //
        $vehiculeForm->handleRequest($request);

        if ($vehiculeForm->isSubmitted() && $vehiculeForm->isValid()) {
            //generate the slug 
            $slug = $slugger->slug(sprintf("%s - %s", $vehicule->getBrand()->getLabelBrand(), $vehicule->getModel()));
            $vehicule->setSlug($slug);

            //get the user
            $user = $security->getUser(); 
            $vehicule->setUserCreator($user);

            //stock data
            $em->persist($vehicule);
            $em->flush();

            $this->addFlash('success', 'Vehicule ' . $vehicule->getSlug() . 'ajouté avec succés !');


            // redirect
            return $this->redirectToRoute('list_vehicules');
        }
        return $this->render('vehicule/create.html.twig', compact('vehiculeForm'));
    }

    #[Route('vehicules/edit/{id}', name: 'edit_vehicule')]
    #[IsGranted('ROLE_EDITOR')]
    public function edit(
        Vehicule $vehicule,
        Request $request,
        EntityManagerInterface $em,
        SluggerInterface $slugger
    ): Response {

        if($vehicule){
            $this->denyAccessUnlessGranted('vehicule.is_creator', $vehicule);
        }

        //Create the form
        $vehiculeForm = $this->createForm(VehiculesFormType::class, $vehicule);

        //treat the request
        $vehiculeForm->handleRequest($request);

        if ($vehiculeForm->isSubmitted() && $vehiculeForm->isValid()) {
            //generate the slug
            $slug = $slugger->slug(sprintf("%s - %s", $vehicule->getBrand()->getLabelBrand(), $vehicule->getModel()));
            $vehicule->setSlug($slug);

            //stock data
            $em->persist($vehicule);
            $em->flush();

            $this->addFlash('success', 'Vehicule ' . $vehicule->getSlug() . ' modifié avec succés !');

            return $this->redirectToRoute('list_vehicules');
        }

        return $this->render('vehicule/edit.html.twig', compact('vehiculeForm'));
    }

    #[Route('vehicules/delete/{id}', name: 'delete_vehicule')]
    #[IsGranted('ROLE_EDITOR')]
    public function delete(Vehicule $vehicule, EntityManagerInterface $em): Response
    {
        if ($vehicule) {
            $this->denyAccessUnlessGranted('vehicule.is_creator', $vehicule);
        }

        $em->remove($vehicule);
        $em->flush();

        $this->addFlash('success', 'Vehicule ' . $vehicule->getSlug() . ' supprimé avec succés !');

        return $this->redirectToRoute('list_vehicules');
    }

}
