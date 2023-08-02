<?php

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Entity\WebProject;
use App\Form\WebProjectType;
use App\Repository\WebProjectRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[Route('/admin/webproject'), IsGranted('ROLE_ADMIN')]
class WebProjectAdminController extends BaseController
{
    #[Route('/', name: 'webproject_admin_index', methods: ['GET'])]
    public function index(WebProjectRepository $webProjectRepository): Response
    {
        return $this->render('admin/webproject/index.html.twig', [
            'web_projects' => $webProjectRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'webproject_admin_new', methods: ['GET', 'POST'])]
    public function new(Request $request, WebProjectRepository $webProjectRepository): Response
    {
        $webProject = new WebProject();
        $form = $this->createForm(WebProjectType::class, $webProject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $webProjectRepository->save($webProject, true);

            $this->addFlash('success', "Project created");

            return $this->redirectToRoute('webproject_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/webproject/new.html.twig', [
            'web_project' => $webProject,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'webproject_admin_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, WebProject $webProject, WebProjectRepository $webProjectRepository): Response
    {
        $form = $this->createForm(WebProjectType::class, $webProject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $webProjectRepository->save($webProject, true);

            $this->addFlash('success', "Project edited");

            return $this->redirectToRoute('webproject_admin_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/webproject/edit.html.twig', [
            'web_project' => $webProject,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'webproject_admin_delete', methods: ['POST'])]
    public function delete(Request $request, WebProject $webProject, WebProjectRepository $webProjectRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$webProject->getId(), $request->request->get('_token'))) {
            $webProjectRepository->remove($webProject, true);
        }

        $this->addFlash('success', "Project removed");

        return $this->redirectToRoute('webproject_admin_index', [], Response::HTTP_SEE_OTHER);
    }
}
