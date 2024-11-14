<?php

namespace App\Controller;

use App\Entity\Wheel;
use App\Repository\WheelRepository;
use App\Repository\WheelRepositoryInterface;
use App\Form\WheelFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

// ...

class WheelController extends AbstractController
{
    public function __construct(
        #[Autowire(service: WheelRepository::class)]
        private wheelRepositoryInterface $wheelRepository
    ) {
    }

    #[Route(path: '/wheels', name: 'list_wheels')]
    #[IsGranted('ROLE_USER')]
    public function showAllWheel(): Response
    {
        $wheels = $this->wheelRepository->findAll();

        if (!$wheels) {
            throw $this->createNotFoundException(
                'No wheels found'
            );
        }

        return $this->render('wheel/list.html.twig', ['wheels' => $wheels]);
    }

    #[Route(path: '/wheel/{id}', name: 'show_wheel', priority : 1)]
    #[IsGranted('ROLE_USER')]
    public function showWheel(int $id): Response
    {
        $wheel = $this->wheelRepository->find($id);

        if (!$wheel) {
            throw $this->createNotFoundException(
                'No wheel found for id ' . $id
            );
        }

        return $this->render('list.html.twig', ['wheel' => $wheel]);
    }

    #[Route(path: 'wheel/ajout', name: 'add_wheel', priority : 2)]
    #[IsGranted('ROLE_EDITOR')]
    public function add(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        //Create a new wheel
        $wheel = new wheel();

        //Create the form
        $wheelForm = $this->createForm(WheelFormType ::class, $wheel);

        //
        $wheelForm->handleRequest($request);

        if ($wheelForm->isSubmitted() && $wheelForm->isValid()) {
            //stock data
            $em->persist($wheel);
            $em->flush();

            $this->addFlash('success', 'wheel ajouté avec succés !');

            // redirect
            // return $this->redirectToRoute('index');
        }

        return $this->render('wheel/create.html.twig', compact('wheelForm'));
    }

    #[Route('wheels/edit/{id}', name: 'edit_wheel')]
    #[IsGranted('ROLE_EDITOR')]
    public function edit(
        Wheel $wheel,
        Request $request,
        EntityManagerInterface $em,
        SluggerInterface $slugger
    ): Response {
        //Create the form
        $wheelForm = $this->createForm(WheelFormType::class, $wheel);

        //treat the request
        $wheelForm->handleRequest($request);

        if ($wheelForm->isSubmitted() && $wheelForm->isValid()) {
            //stock data
            $em->persist($wheel);
            $em->flush();

            $this->addFlash('success', 'wheel ajouté avec succés !');

            return $this->redirectToRoute('list_wheels');
        }
        
        return $this->render('wheel/edit.html.twig', compact('wheelForm'));
    }

    #[Route('wheels/delete/{id}', name: 'delete_wheel')]
    #[IsGranted('ROLE_EDITOR')]
    public function delete(Wheel $wheel, EntityManagerInterface $em): Response
    {
        $em->remove($wheel);
        $em->flush();

        return $this->redirectToRoute('list_wheels');
    }
}
