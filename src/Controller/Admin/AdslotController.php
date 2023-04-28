<?php

namespace App\Controller\Admin;

use App\Entity\Adslot;
use App\Form\AdslotType;
use App\Repository\AdslotRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[Route('/admin/adslot'), IsGranted('ROLE_ADMIN')]
class AdslotController extends AbstractController
{
    #[Route('/', name: 'app_adslot_index', methods: ['GET'])]
    public function index(AdslotRepository $adslotRepository): Response
    {
        return $this->render('admin/adslot/index.html.twig', [
            'adslots' => $adslotRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_adslot_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AdslotRepository $adslotRepository): Response
    {
        $adslot = new Adslot();
        $form = $this->createForm(AdslotType::class, $adslot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $adslotRepository->save($adslot, true);

            return $this->redirectToRoute('app_adslot_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/adslot/new.html.twig', [
            'adslot' => $adslot,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_adslot_show', methods: ['GET'])]
    public function show(Adslot $adslot): Response
    {
        return $this->render('admin/adslot/show.html.twig', [
            'adslot' => $adslot,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_adslot_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Adslot $adslot, AdslotRepository $adslotRepository): Response
    {
        $form = $this->createForm(AdslotType::class, $adslot);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $adslotRepository->save($adslot, true);

            return $this->redirectToRoute('app_adslot_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/adslot/edit.html.twig', [
            'adslot' => $adslot,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_adslot_delete', methods: ['POST'])]
    public function delete(Request $request, Adslot $adslot, AdslotRepository $adslotRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$adslot->getId(), $request->request->get('_token'))) {
            $adslotRepository->remove($adslot, true);
        }

        return $this->redirectToRoute('app_adslot_index', [], Response::HTTP_SEE_OTHER);
    }
}
