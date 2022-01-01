<?php

namespace App\Controller;

use App\Entity\Image;
use App\Form\ImageType;
use App\Repository\ImageRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PortfolioController extends AbstractController
{
    #[Route('/portfolio', name: 'portfolio')]
    public function index(ImageRepository $imageRepository): Response
    {
        return $this->render('portfolio/index.html.twig', [
            'images' => $imageRepository->findAll(),
        ]);
    }

    #[Route('/admin/portfolio/new', name: 'portfolio_new')]
    public function image(Request $request, ManagerRegistry $doctrine): Response
    {
        $image = new Image;
        $form = $this->createForm(ImageType::class, $image);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($image);
            $entityManager->flush();

            return $this->redirectToRoute('portfolio');
        }

        return $this->renderForm('portfolio/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/admin/portfolio/{id}/edit', name: 'portfolio_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Image $image, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $doctrine->getManager()->flush();

            return $this->redirectToRoute('portfolio', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('portfolio/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/admin/portfolio/{id}/delete', name: 'portfolio_delete', methods: ['POST'])]
    public function delete(Request $request, Image $image, ManagerRegistry $doctrine): Response
    {
        if ($this->isCsrfTokenValid('delete'.$image->getId(), $request->request->get('_token'))) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($image);
            $entityManager->flush();
        }

        return $this->redirectToRoute('portfolio', [], Response::HTTP_SEE_OTHER);
    }
}
