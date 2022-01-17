<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/article')]
class ArticleController extends BaseController
{
    #[Route('/', name: 'article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository, Request $request): Response
    {
        $articles = $articleRepository->findAllPublished($request->get('page', 1));

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
            'menu' => 'articles'
        ]);
    }

    #[Route('/{slug}', name: 'article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        if (false === $article->getPublished() && false === $this->isGranted('ROLE_ADMIN')) {
            throw $this->createNotFoundException();
        }

        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }
}
