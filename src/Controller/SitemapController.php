<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\ImageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SitemapController extends AbstractController
{
    #[Route('/sitemap.xml', name: 'sitemap', defaults:["_format" => "xml"])]
    public function index(ImageRepository $imageRepository, ArticleRepository $articleRepository): Response
    {
        $urls = [];

        $urls[] = ['loc' => $this->generateUrl("default", [], UrlGeneratorInterface::ABSOLUTE_URL)];
        $urls[] = ['loc' => $this->generateUrl("portfolio_index", [], UrlGeneratorInterface::ABSOLUTE_URL)];
        $urls[] = ['loc' => $this->generateUrl("article_index", [], UrlGeneratorInterface::ABSOLUTE_URL)];

        $images = $imageRepository->findAll();
        foreach ($images as $image) {
            $urls[] = [
                'loc' => $this->generateUrl("portfolio_show", ['id' => $image->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
                'lastmod' => $image->getCreatedAt()->format('Y-m-d')
            ];
        } 

        $articles = $articleRepository->findBy(['published' => true]);
        foreach ($articles as $article) {
            $urls[] = [
                'loc' => $this->generateUrl("article_show", ['slug' => $article->getSlug()], UrlGeneratorInterface::ABSOLUTE_URL),
                'lastmod' => $article->getCreatedAt()->format('Y-m-d')
            ];
        }

        $response = new Response(
            $this->renderView('sitemap/index.html.twig', ['urls' => $urls]),
            200
        );
        $response->headers->set('Content-Type', 'text/xml');
        
        return $response;
    }
}
