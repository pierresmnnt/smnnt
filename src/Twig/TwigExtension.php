<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class TwigExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('icon', [$this, 'svgIcon'], ['is_safe' => ['html']]),
            new TwigFunction('menu', [$this, 'menuActive'], ['is_safe' => ['html'], 'needs_context' => true]),
        ];
    }

    public function svgIcon(string $id, ?int $size = null): string
    {
        $attributes = '';
        if ($size) {
            $attributes = "width=\"{$size}px\" height=\"{$size}px\"";
        }

        return <<<HTML
        <svg class="icon icon-{$id}" {$attributes} aria-hidden="true"><use xlink:href="/sprite.svg#{$id}"></use></svg>
        HTML;
    }

    public function menuActive(array $context, string $name): string
    {
        if (($context['menu'] ?? null) === $name) {
            return ' aria-current="page"';
        }

        return '';
    }
}
