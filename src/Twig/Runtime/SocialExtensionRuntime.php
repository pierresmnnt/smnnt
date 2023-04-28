<?php

namespace App\Twig\Runtime;

use App\Repository\UserRepository;
use Symfony\Contracts\Cache\CacheInterface;
use Twig\Environment;
use Twig\Extension\RuntimeExtensionInterface;

class SocialExtensionRuntime implements RuntimeExtensionInterface
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

    public function getSocials(): string
    {
        return $this->cache->get('socials', function (){
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
