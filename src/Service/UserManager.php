<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserManager
{
    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $userPasswordHasherInterface;

    public function __construct(EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasherInterface)
    {
        $this->entityManager = $entityManager;
        $this->userPasswordHasherInterface = $userPasswordHasherInterface;
    }

    public function createUser(string $email, string $password, string $role): User
    {
        $user = new User();
        $user
            ->setEmail($email)
            ->setPassword($this->userPasswordHasherInterface->hashPassword($user, $password))
            ->setRoles([$role]);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}