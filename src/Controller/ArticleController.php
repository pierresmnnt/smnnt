<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Article;
use App\Form\ArticleSearchType;
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
        $data = new SearchData();
        $data->setPage($request->get('page', 1));

        $form = $this->createForm(ArticleSearchType::class, $data);
        $form->handleRequest($request);

        /**
         * @var Object $articles
         */
        $articles = $articleRepository->findArticleSearch($data);

        if ($request->get('ajax')) {
            return $this->json(
                [
                    'content' => $this->renderView('article/_articles.html.twig', ['articles' => $articles]),
                    'pagination' => $this->renderView('article/_pagination.html.twig', ['articles' => $articles]),
                    'pageCount' => ceil($articles->getTotalItemCount() / $articles->getItemNumberPerPage())
                ]);
        }

        return $this->renderForm('article/index.html.twig', [
            'articles' => $articles,
            'form' => $form,
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
            'menu' => 'articles'
        ]);
    }
}
