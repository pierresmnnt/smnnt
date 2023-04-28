<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\SocialExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class SocialExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('socials', [SocialExtensionRuntime::class, 'getSocials'], ['is_safe' => ['html']]),
        ];
    }
}
