<?php

namespace App\Twig\Runtime;

use App\Repository\AdslotRepository;
use App\Repository\AdvertRepository;
use Twig\Environment;
use Twig\Extension\RuntimeExtensionInterface;

class AdslotExtensionRuntime implements RuntimeExtensionInterface
{
    private AdslotRepository $adslotRepository;
    private AdvertRepository $advertRepository;
    private Environment $twig;

    public function __construct(AdslotRepository $adslotRepository, AdvertRepository $advertRepository, Environment $twig)
    {
        $this->adslotRepository = $adslotRepository;
        $this->advertRepository = $advertRepository;
        $this->twig = $twig;
    }

    public function getAdslot($value)
    {
        $advertisement = $this->advertRepository->findOneByAdslot($value);

        return $this->twig->render('admin/adslot/adslot.html.twig', [
            'adslotname' => $value,
            'advertisement' => $advertisement ?? false,
        ]);
    }
}
