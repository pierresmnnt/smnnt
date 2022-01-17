<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Image;
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
            'menu' => 'portfolio'
        ]);
    }

    #[Route('/{id}', name: 'portfolio_show', methods: ['GET'])]
    public function show(Image $image): Response
    {
        return $this->render('portfolio/show.html.twig', [
            'image' => $image,
        ]);
    }
}
