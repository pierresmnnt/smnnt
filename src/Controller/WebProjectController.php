<?php

namespace App\Controller;

use App\Repository\WebProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WebProjectController extends AbstractController
{
    #[Route('/webproject', name: 'webproject_index')]
    public function index(WebProjectRepository $webProjectRepository): Response
    {
        return $this->render('webproject/index.html.twig', [
            'web_projects' => $webProjectRepository->findAll(),
            'menu' => 'webproject'
        ]);
    }
}
