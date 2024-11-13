<?php

namespace App\services;

use App\Entities\User;
use DateTime;
use Doctrine\ORM\EntityManager;

class UserManager
{
    public function __construct(private EntityManager $entityManager) {}

    public function register(string $firstName, string $lastName, string $username, string $email, string $phoneNumber, string $password): void
    {
        $user = new User;
        $user->username = $username;
        $user->email = $email;
        $user->passwordHash = password_hash($password, PASSWORD_BCRYPT);
        $user->phoneNumber = $phoneNumber;
        $user->firstName = $firstName;
        $user->lastName = $lastName;
        $user->isDeleted = false;
        $user->createdAt = new DateTime;

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function hasUsername(string $username): bool
    {
        return $this->entityManager->getRepository(User::class)->findOneBy(['username' => $username]) !== null;
    }

    public function hasEmail(string $email): bool
    {
        return $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]) !== null;
    }

    public function findById(mixed $id): ?User
    {
        return $this->entityManager->getRepository(User::class)->findOneBy(['id' => $id]);
    }

    public function findByUsername(string $username): ?User
    {
        return $this->entityManager->getRepository(User::class)->findOneBy(['username' => $username]);
    }

    public function findByEmail(string $email): ?User
    {
        return $this->entityManager->getRepository(User::class)->findOneBy(['username' => $email]);
    }
}
