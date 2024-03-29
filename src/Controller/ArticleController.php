<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Article;
use App\Form\SearchType;
use App\Repository\AdslotRepository;
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

        $form = $this->createForm(SearchType::class, $data, ['controller' => "article"]);
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

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
            'form' => $form,
            'menu' => 'articles'
        ]);
    }

    #[Route('/{slug}', name: 'article_show', methods: ['GET'])]
    public function show(ArticleRepository $articleRepository, Article $article): Response
    {
        if (false === $article->getPublished()) {
            $this->denyAccessUnlessGranted('POST_VIEW', $article);
        }

        $recommended = $articleRepository->findRecommendedArticle($article->getId(), $article->getTopics());

        return $this->render('article/show.html.twig', [
            'article' => $article,
            'recommended' => $recommended,
            'menu' => 'articles'
        ]);
    }
}
