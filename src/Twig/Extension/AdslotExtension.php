<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\AdslotExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AdslotExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('adslot', [AdslotExtensionRuntime::class, 'getAdslot'], ['is_safe' => ['html']]),
        ];
    }
}
