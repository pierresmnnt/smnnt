<?php

namespace App\Twig\Runtime;

use App\Repository\AdslotRepository;
use Twig\Environment;
use Twig\Extension\RuntimeExtensionInterface;

class AdslotExtensionRuntime implements RuntimeExtensionInterface
{
    private AdslotRepository $adslotRepository;
    private Environment $twig;

    public function __construct(AdslotRepository $adslotRepository, Environment $twig)
    {
        $this->adslotRepository = $adslotRepository;
        $this->twig = $twig;
    }

    public function getAdslot($value)
    {
        $adslot = $this->adslotRepository->findOneByName($value);

        if(!$adslot) return;

        return $this->twig->render('admin/adslot/adslot.html.twig', [
            'adslotname' => $adslot->getName(),
            'advertisement' => $adslot->getAdvert(),
        ]);
    }
}
