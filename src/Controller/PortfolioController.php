<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Image;
use App\Form\ImageType;
use App\Form\SearchType;
use App\Repository\ImageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/portfolio')]
class PortfolioController extends BaseController
{
    #[Route('/', name: 'portfolio_index', methods: ['GET'])]
    public function index(ImageRepository $imageRepository, Request $request): Response
    {
        $data = new SearchData();
        $data->setPage($request->get('page', 1));

        $form = $this->createForm(SearchType::class, $data);
        $form->handleRequest($request);

        $images = $imageRepository->findSearch($data);

        if ($request->get('ajax')) {
            return $this->json(
                [
                    'content' => $this->renderView('portfolio/_images.html.twig', ['images' => $images]),
                    'pagination' => $this->renderView('portfolio/_pagination.html.twig', ['images' => $images]),
                    'pageCount' => ceil($images->getTotalItemCount() / $images->getItemNumberPerPage())
                ]);
        }

        return $this->renderForm('portfolio/index.html.twig', [
            'images' => $images,
            'form' => $form,
        ]);
    }

    #[Route('/new', name: 'portfolio_new', methods: ['GET', 'POST'])]
    public function image(Request $request): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $image = new Image;
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getEntityManager()->persist($image);
            $this->getEntityManager()->flush();

            return $this->redirectToRoute('portfolio_index');
        }

        return $this->renderForm('portfolio/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'portfolio_show', methods: ['GET'])]
    public function show(Image $image): Response
    {
        return $this->render('portfolio/show.html.twig', [
            'image' => $image,
        ]);
    }

    #[Route('/{id}/edit', name: 'portfolio_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Image $image): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getEntityManager()->flush();

            return $this->redirectToRoute('portfolio_show', ['id' => $image->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('portfolio/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'portfolio_delete', methods: ['POST'])]
    public function delete(Request $request, Image $image): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        if ($this->isCsrfTokenValid('delete'.$image->getId(), $request->request->get('_token'))) {
            $this->getEntityManager()->remove($image);
            $this->getEntityManager()->flush();
        }

        return $this->redirectToRoute('portfolio_index', [], Response::HTTP_SEE_OTHER);
    }
}
