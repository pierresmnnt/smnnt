<?php

namespace App\Controller\Admin;

use App\Entity\Advert;
use App\Form\AdvertType;
use App\Repository\AdvertRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[Route('/admin/advert'), IsGranted('ROLE_ADMIN')]
class AdvertController extends AbstractController
{
    #[Route('/', name: 'app_advertisement_index', methods: ['GET'])]
    public function index(AdvertRepository $advertisementRepository): Response
    {
        return $this->render('admin/advert/index.html.twig', [
            'advertisements' => $advertisementRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_advertisement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AdvertRepository $advertisementRepository): Response
    {
        $advertisement = new Advert();
        $form = $this->createForm(AdvertType::class, $advertisement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $advertisementRepository->save($advertisement, true);

            return $this->redirectToRoute('app_advertisement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/advert/new.html.twig', [
            'advertisement' => $advertisement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_advertisement_show', methods: ['GET'])]
    public function show(Advert $advertisement): Response
    {
        return $this->render('admin/advert/show.html.twig', [
            'advertisement' => $advertisement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_advertisement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Advert $advertisement, AdvertRepository $advertisementRepository): Response
    {
        $form = $this->createForm(AdvertType::class, $advertisement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $advertisementRepository->save($advertisement, true);

            return $this->redirectToRoute('app_advertisement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/advert/edit.html.twig', [
            'advertisement' => $advertisement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_advertisement_delete', methods: ['POST'])]
    public function delete(Request $request, Advert $advertisement, AdvertRepository $advertisementRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$advertisement->getId(), $request->request->get('_token'))) {
            $advertisementRepository->remove($advertisement, true);
        }

        return $this->redirectToRoute('app_advertisement_index', [], Response::HTTP_SEE_OTHER);
    }
}
