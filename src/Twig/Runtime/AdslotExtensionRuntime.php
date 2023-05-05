<?php

namespace App\Twig\Runtime;

use App\Repository\AdslotRepository;
use App\Repository\AdvertRepository;
use Twig\Environment;
use Twig\Extension\RuntimeExtensionInterface;

class AdslotExtensionRuntime implements RuntimeExtensionInterface
{
    private AdvertRepository $advertRepository;
    private Environment $twig;

    public function __construct(AdvertRepository $advertRepository, Environment $twig)
    {
        $this->advertRepository = $advertRepository;
        $this->twig = $twig;
    }

    public function getAdslot(string $value, array $context = null)
    {
        $advertisements = $this->advertRepository->findByAdslot($value, $context);

        if (count($advertisements) > 1) {
            $advertisement = $advertisements[array_rand($advertisements)];
        } else if (count($advertisements) === 1) {
            $advertisement = $advertisements[0];
        } else {
            $advertisement = false;
        }

        return $this->twig->render('admin/adslot/adslot.html.twig', [
            'adslotname' => $value,
            'advertisement' => $advertisement,
        ]);
    }
}
