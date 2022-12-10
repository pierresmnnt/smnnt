<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Cache\CacheInterface;

/**
 * @method User getUser()
 */
class BaseController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private EventDispatcherInterface $eventDispatcher;
    private CacheInterface $cacheInterface;

    public function __construct(EntityManagerInterface $entityManagerInterface, EventDispatcherInterface $eventDispatcher, CacheInterface $cacheInterface)
    {
        $this->entityManager = $entityManagerInterface;
        $this->eventDispatcher = $eventDispatcher;
        $this->cacheInterface = $cacheInterface;
    }

    protected function getEntityManager()
    {
        return $this->entityManager;
    }

    protected function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }
    protected function getCacheInterface()
    {
        return $this->cacheInterface;
    }
}