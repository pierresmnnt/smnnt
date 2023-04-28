<?php

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Entity\Gear;
use App\Form\GearType;
use App\Repository\GearRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/gear'), IsGranted('ROLE_ADMIN')]
class GearController extends BaseController
{
    #[Route('/', name: 'app_gear_index', methods: ['GET'])]
    public function index(GearRepository $gearRepository): Response
    {
        return $this->render('admin/gear/index.html.twig', [
            'gears' => $gearRepository->findAll(),
            'menu' => 'admin-gear'
        ]);
    }

    #[Route('/new', name: 'app_gear_new', methods: ['GET', 'POST'])]
    public function new(Request $request, GearRepository $gearRepository): Response
    {
        $gear = new Gear();
        $form = $this->createForm(GearType::class, $gear);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gearRepository->add($gear, true);

            $this->addFlash('success', "New gear created");
            return $this->redirectToRoute('app_gear_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/gear/new.html.twig', [
            'gear' => $gear,
            'form' => $form,
            'menu' => 'admin-gear'
        ]);
    }

    #[Route('/{id}/edit', name: 'app_gear_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Gear $gear, GearRepository $gearRepository): Response
    {
        $form = $this->createForm(GearType::class, $gear);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gearRepository->add($gear, true);

            $this->addFlash('success', "Gear edited");
            return $this->redirectToRoute('app_gear_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/gear/edit.html.twig', [
            'gear' => $gear,
            'form' => $form,
            'menu' => 'admin-gear'
        ]);
    }

    #[Route('/{id}', name: 'app_gear_delete', methods: ['POST'])]
    public function delete(Request $request, Gear $gear, GearRepository $gearRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gear->getId(), $request->request->get('_token'))) {
            $gearRepository->remove($gear, true);
        }

        $this->addFlash('success', "Gear removed");
        return $this->redirectToRoute('app_gear_index', [], Response::HTTP_SEE_OTHER);
    }
}
