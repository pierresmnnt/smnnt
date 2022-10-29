<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @method User getUser()
 */
class BaseController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(EntityManagerInterface $entityManagerInterface, EventDispatcherInterface $eventDispatcher)
    {
        $this->entityManager = $entityManagerInterface;
        $this->eventDispatcher = $eventDispatcher;
    }

    protected function getEntityManager()
    {
        return $this->entityManager;
    }

    protected function getEventDispatcher()
    {
        return $this->eventDispatcher;
    }
}