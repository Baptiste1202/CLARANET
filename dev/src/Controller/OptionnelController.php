<?php

// src/Controller/ProductController.php
namespace App\Controller;

use App\Entity\Optionnel;
use App\Repository\OptionnelRepository;
use App\Repository\OptionnelRepositoryInterface;
use App\Form\OptionnelFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

// ...

class OptionnelController extends AbstractController
{
    public function __construct(
        #[Autowire(service: OptionnelRepository::class)]
        private OptionnelRepositoryInterface $optionnelRepository
    ) {
    }

    #[Route(path: '/optionnels', name: 'list_optionnels')]
    #[IsGranted('ROLE_USER')]
    public function showAll(): Response
    {
        $optionnels = $this->optionnelRepository->findAll();

        return $this->render('optionnel/list.html.twig', ['optionnels' => $optionnels]);
    }

    #[Route(path: '/optionnel/{id}', name: 'show_optionnel')]
    #[IsGranted('ROLE_USER')]
    public function show(int $id): Response
    {
        $optionnel = $this->optionnelRepository->find($id);

        if (!$optionnel) {
            throw $this->createNotFoundException(
                'No product found for id ' . $id
            );
        }
        return $this->render('optionnel/list.html.twig', ['optionnels' => $optionnel]);
    }

    #[Route(path: 'optionnels/ajout', name: 'add_optionnel')]
    #[IsGranted('ROLE_EDITOR')]
    public function add(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        //Create a new optionnel
        $optionnel = new Optionnel();

        //Create the form
        $optionnelForm = $this->createForm(OptionnelFormType ::class, $optionnel);

        //
        $optionnelForm->handleRequest($request);

        if ($optionnelForm->isSubmitted() && $optionnelForm->isValid()) {

            //stock data
            $em->persist($optionnel);
            $em->flush();

            $this->addFlash('success', 'optionnel ajouté avec succés !');

            // redirect
            // return $this->redirectToRoute('index');
        }

        return $this->render('optionnel/create.html.twig', compact('optionnelForm'));
    }

    #[Route('optionnels/edit/{id}', name: 'edit_optionnel')]
    #[IsGranted('ROLE_EDITOR')]
    public function edit(
        Optionnel $optionnel,
        Request $request,
        EntityManagerInterface $em,
        SluggerInterface $slugger
    ): Response {
        //Create the form
        $optionnelForm = $this->createForm(OptionnelFormType::class, $optionnel);

        //treat the request
        $optionnelForm->handleRequest($request);

        if ($optionnelForm->isSubmitted() && $optionnelForm->isValid()) {

            //stock data
            $em->persist($optionnel);
            $em->flush();

            $this->addFlash('success', 'optionnel ajouté avec succés !');

            return $this->redirectToRoute('list_optionnels');
        }
        
        return $this->render('optionnel/edit.html.twig', compact('optionnelForm'));
    }

    #[Route('optionnels/delete/{id}', name: 'delete_optionnel')]
    #[IsGranted('ROLE_EDITOR')]
    public function delete(Optionnel $optionnel, EntityManagerInterface $em): Response
    {
        $em->remove($optionnel);
        $em->flush();

        return $this->redirectToRoute('list_optionnels');
    }
}
