<?php

namespace App\Twig;

use App\Repository\UserRepository;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class SocialsExtension extends AbstractExtension
{
    private UserRepository $userRepository;
    private Environment $twig;
    private CacheInterface $cache;

    public function __construct(UserRepository $userRepository, Environment $twig, CacheInterface $cache)
    {
        $this->userRepository = $userRepository;
        $this->twig = $twig;    
        $this->cache = $cache;
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('socials', [$this, 'getSocials'], ['is_safe' => ['html']]),
        ];
    }

    public function getSocials(): string
    {
        return $this->cache->get('socials', function (ItemInterface $item){
            $item->expiresAfter(3600);

            return $this->renderSocials();
        });
    }

    private function renderSocials(): string
    {
        $user = $this->userRepository->findAll()[0];

        return $this->twig->render('partials/_socials.html.twig', [
            'user' => $user,
        ]);
    }
}
