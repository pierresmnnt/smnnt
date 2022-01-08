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
    public function index(ImageRepository $imageRepository): Response
    {
        return $this->renderForm('admin/portfolio/index.html.twig', [
            'images' => $imageRepository->findBy([], ['id' => 'DESC']),
        ]);
    }

    #[Route('/new', name: 'admin_portfolio_new', methods: ['GET', 'POST'])]
    public function image(Request $request): Response
    {
        $image = new Image;
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getEntityManager()->persist($image);
            $this->getEntityManager()->flush();

            return $this->redirectToRoute('admin_portfolio_index');
        }

        return $this->renderForm('admin/portfolio/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_portfolio_show', methods: ['GET'])]
    public function show(Image $image): Response
    {
        return $this->render('admin/portfolio/show.html.twig', [
            'image' => $image,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_portfolio_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Image $image): Response
    {
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getEntityManager()->flush();

            return $this->redirectToRoute('admin_portfolio_show', ['id' => $image->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/portfolio/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_portfolio_delete', methods: ['POST'])]
    public function delete(Request $request, Image $image): Response
    {        
        if ($this->isCsrfTokenValid('delete'.$image->getId(), $request->request->get('_token'))) {
            $this->getEntityManager()->remove($image);
            $this->getEntityManager()->flush();
        }

        return $this->redirectToRoute('admin_portfolio_index', [], Response::HTTP_SEE_OTHER);
    }
}