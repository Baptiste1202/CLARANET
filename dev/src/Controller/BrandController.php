<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Repository\BrandRepository;
use App\Repository\BrandRepositoryInterface;
use App\Form\BrandFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;

// ...

class BrandController extends AbstractController
{
    public function __construct(
        #[Autowire(service: BrandRepository::class)]
        private BrandRepositoryInterface $brandRepository
    ) {
    }

    #[Route(path: '/brand', name: 'list_brand')]
    #[IsGranted('ROLE_USER')]
    public function showAllBrand(): Response
    {

        $brands = $this->brandRepository->findAll();

        if (!$brands) {
            throw $this->createNotFoundException(
                'No brands found'
            );
        }

        return $this->render('brand/list.html.twig', ['brands' => $brands]);
    }

    #[Route(path: '/brand/{id}', name: 'show_brand')]
    #[IsGranted('ROLE_USER')]
    public function showBrand(int $id): Response
    {
        $brand = $this->brandRepository->find($id);

        if (!$brand) {
            throw $this->createNotFoundException(
                'No brand found for id ' . $id
            );
        }

        return $this->render('base.html.twig', ['brand' => $brand]);
    }

    #[Route(path: 'brand/ajout', name: 'add_brand')]
    #[IsGranted('ROLE_EDITOR')]
    public function add(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        //Create a new brand
        $brand = new Brand();

        //Create the form
        $brandForm = $this->createForm(BrandFormType::class, $brand);

        //
        $brandForm->handleRequest($request);

        if ($brandForm->isSubmitted() && $brandForm->isValid()) {
            //stock data
            $em->persist($brand);
            $em->flush();

            $this->addFlash('success', 'brand ajouté avec succés !');

            // redirect
            // return $this->redirectToRoute('index');
        }
        return $this->render('addbrand.html.twig', compact('brandForm'));
    }

    #[Route('brands/edit/{id}', name: 'edit_brand')]
    #[IsGranted('ROLE_EDITOR')]
    public function edit(
        Brand $brand,
        Request $request,
        EntityManagerInterface $em,
        SluggerInterface $slugger
    ): Response {
        //Create the form
        $brandForm = $this->createForm(BrandFormType::class, $brand);

        //treat the request
        $brandForm->handleRequest($request);

        if ($brandForm->isSubmitted() && $brandForm->isValid()) {
            //stock data
            $em->persist($brand);
            $em->flush();

            $this->addFlash('success', 'brand ajouté avec succés !');

            return $this->redirectToRoute('list_brands');
        }
        return $this->render('editbrand.html.twig', compact('brandForm'));
    }

    #[Route('brands/delete/{id}', name: 'delete_brand')]
    #[IsGranted('ROLE_EDITOR')]
    public function delete(Brand $brand, EntityManagerInterface $em): Response
    {
        $em->remove($brand);
        $em->flush();

        return $this->redirectToRoute('list_brands');
    }
}
