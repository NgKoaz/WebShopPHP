<?php

namespace App\services;

use App\Entities\User;
use DateTime;
use Doctrine\ORM\EntityManager;

class UserManager
{
    public function __construct(private EntityManager $entityManager) {}

    public function register(string $firstname, string $lastname, string $username, string $email, string $phone, string $password): void
    {
        $user = new User;
        $user->username = $username;
        $user->email = $email;
        $user->passwordHash = $this->passwordHash($password);
        $user->phoneNumber = $phone;
        $user->firstName = $firstname;
        $user->lastName = $lastname;
        $user->isDeleted = false;
        $user->createdAt = new DateTime;

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    private function passwordHash($password)
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function hasUsername(string $username): bool
    {
        return $this->entityManager->getRepository(User::class)->findOneBy(['username' => $username]) !== null;
    }

    public function hasEmail(string $email): bool
    {
        return $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]) !== null;
    }

    public function hasId(int $id): bool
    {
        return $this->entityManager->getRepository(User::class)->findOneBy(['id' =>  $id]) !== null;
    }

    public function findById(int $id): ?User
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

    public function getUsersWithNumPage(int $page, int $limit): array
    {
        $count = $this->entityManager->getRepository(User::class)->count([]);
        $totalPages = ceil($count / $limit);
        $page = ($page < 1) ? 1 : $page;
        $offset = ($page - 1) * $limit;
        $users = $this->entityManager->getRepository(User::class)->findBy(criteria: [], limit: $limit, offset: $offset);
        return [
            "users" => $users,
            "totalPages" => $totalPages,
            "currentPage" => $page
        ];
    }

    public function editUserById(int $id, string $firstname, string $lastname, string $phone, bool $isDeleted, string $password = ""): bool
    {
        $user = $this->findById($id);
        if ($user === null) return false;

        $user->firstName = $firstname;
        $user->lastName = $lastname;
        $user->phoneNumber = $phone;

        if ($isDeleted == false && $user->isDeleted == true) {
            $user->deleteAt = new DateTime;
        }
        $user->isDeleted = $isDeleted;

        if (strlen($password) > 0) {
            $user->passwordHash = $this->passwordHash($password);
        }
        $this->entityManager->flush();
        return true;
    }

    public function deleteUserById(int $id)
    {
        /**
         * @var User
         */
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $id]);
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }
}
