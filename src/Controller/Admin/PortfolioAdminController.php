<?php

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Entity\Image;
use App\Form\ImageType;
use App\Repository\ImageRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/portfolio'), IsGranted('ROLE_ADMIN')]
class PortfolioAdminController extends BaseController
{
    #[Route('/', name: 'admin_portfolio_index', methods: ['GET'])]
    public function index(ImageRepository $imageRepository, Request $request): Response
    {
        $imagesCount = $imageRepository->count(['isInPortfolio' => true]);

        return $this->render('admin/portfolio/index.html.twig', [
            'count' => $imagesCount,
            'images' => $imageRepository->findAllWithJoin($request->get('page', 1), true),
            'menu' => 'admin-portfolio'
        ]);
    }

    #[Route('/media', name: 'admin_media_index', methods: ['GET'])]
    public function media(ImageRepository $imageRepository, Request $request): Response
    {
        $imagesCount = $imageRepository->count(['isInPortfolio' => false]);

        return $this->render('admin/portfolio/media.html.twig', [
            'count' => $imagesCount,
            'images' => $imageRepository->findAllWithJoin($request->get('page', 1), false),
            'menu' => 'admin-portfolio'
        ]);
    }

    #[Route('/new', name: 'admin_portfolio_new', methods: ['GET', 'POST'])]
    public function image(Request $request): Response
    {
        $image = new Image;
        if($request->get('portfolio') === "false"){
            $image->setIsInPortfolio(false);
        }
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getEntityManager()->persist($image);
            $this->getEntityManager()->flush();

            $this->addFlash('success', "New photo added");

            $redirect = $image->getIsInPortfolio() ? "admin_portfolio_index" : "admin_media_index";

            return $this->redirectToRoute($redirect);
        }

        return $this->renderForm('admin/portfolio/new.html.twig', [
            'form' => $form,
            'menu' => 'admin-portfolio'
        ]);
    }

    #[Route('/{id}', name: 'admin_portfolio_show', methods: ['GET'])]
    public function show(int $id, ImageRepository $imageRepository): Response
    {
        return $this->render('admin/portfolio/show.html.twig', [
            'image' => $imageRepository->findImageById($id),
            'menu' => 'admin-portfolio'
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_portfolio_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Image $image): Response
    {
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getEntityManager()->flush();

            $this->addFlash('success', "Photo edited");

            return $this->redirectToRoute('admin_portfolio_show', ['id' => $image->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/portfolio/new.html.twig', [
            'form' => $form,
            'menu' => 'admin-portfolio'
        ]);
    }

    #[Route('/{id}', name: 'admin_portfolio_delete', methods: ['POST'])]
    public function delete(Request $request, Image $image): Response
    {        
        $redirect = $image->getIsInPortfolio() ? "admin_portfolio_index" : "admin_media_index";

        if ($this->isCsrfTokenValid('delete'.$image->getId(), $request->request->get('_token'))) {
            $this->getEntityManager()->remove($image);
            $this->getEntityManager()->flush();
        }

        $this->addFlash('success', "Photo removed");

        return $this->redirectToRoute($redirect, [], Response::HTTP_SEE_OTHER);
    }
}
