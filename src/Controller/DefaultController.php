<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\ImageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'default')]
    public function index(ImageRepository $imageRepository, ArticleRepository $articleRepository): Response
    {
        $lastImage = $imageRepository->findLast();
        $lastArticle = $articleRepository->findLastPublished();

        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'image' => $lastImage,
            'article' =>$lastArticle
        ]);
    }

    #[Route('/ui', name: 'interface')]
    public function interface(): Response
    {
        return $this->render('default/interface.html.twig');
    }
}
