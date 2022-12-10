<?php

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends BaseController
{
    #[Route('/', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'menu' => 'admin'
        ]);
    }

    #[Route('/edit', name: 'admin_edit')]
    public function edit(Request $request): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getEntityManager()->flush();

            $this->getCacheInterface()->delete('socials');
            $this->addFlash('success', "User updated");

            return $this->redirectToRoute('admin_edit', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/profile/edit.html.twig', [
            'form' => $form,
            'menu' => 'admin'
        ]);
    }
}
