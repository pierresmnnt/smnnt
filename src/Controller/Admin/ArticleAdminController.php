<?php

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Entity\Article;
use App\Event\PublishedArticleEvent;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Route('/admin/article'), IsGranted('ROLE_ADMIN')]
class ArticleAdminController extends BaseController
{
    #[Route('/', name: 'admin_article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository, Request $request): Response
    {
        $articleCount = $articleRepository->count([]);
        
        return $this->render('admin/article/index.html.twig', [
            'count' => $articleCount,
            'articles' => $articleRepository->findAllWithJoin($request->get('page', 1)),
            'menu' => 'admin-article'
        ]);
    }

    #[Route('/new', name: 'admin_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($article->getPublished() && !$article->getPublishedAt()){
                $event = new PublishedArticleEvent($article);
                $this->getEventDispatcher()->dispatch($event, PublishedArticleEvent::NAME);
            }
            
            $this->getEntityManager()->persist($article);
            $this->getEntityManager()->flush();

            $this->addFlash('success', "New article created");
            return $this->redirectToRoute('admin_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/article/new.html.twig', [
            'article' => $article,
            'form' => $form,
            'menu' => 'admin-article'
        ]);
    }

    #[Route('/{id<\d+>}', name: 'admin_article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        $privateUrl = $article->isPrivateAccess() ? $this->generateUrl('article_show', ['slug' => $article->getSlug(), 'token' => $article->getPrivateAccessToken()], UrlGeneratorInterface::ABSOLUTE_URL) : "";

        return $this->render('admin/article/show.html.twig', [
            'privateUrl' => $privateUrl,
            'article' => $article,
            'menu' => 'admin-article'
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Article $article): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($article->getPublished() && !$article->getPublishedAt()){
                $event = new PublishedArticleEvent($article);
                $this->getEventDispatcher()->dispatch($event, PublishedArticleEvent::NAME);
            }

            // If article has a token, get it, else, create one.
            $currentToken = $article->getPrivateAccessToken() ?: bin2hex(random_bytes(60));
            // If article has private enable and is not published, get current token, else, token is null.
            $token = $article->isPrivateAccess() && !$article->getPublished() ? $currentToken : null;
            $article->setPrivateAccessToken($token);

            $this->getEntityManager()->flush();

            $this->addFlash('success', "Article edited");
            return $this->redirectToRoute('admin_article_show', ['id' => $article->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
            'menu' => 'admin-article'
        ]);
    }

    #[Route('/{id}', name: 'admin_article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $this->getEntityManager()->remove($article);
            $this->getEntityManager()->flush();
        }

        $this->addFlash('success', "Article removed");
        return $this->redirectToRoute('admin_article_index', [], Response::HTTP_SEE_OTHER);
    }
}
