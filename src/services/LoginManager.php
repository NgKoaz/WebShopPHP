<?php

namespace App\services;

use App\Entities\User;
use Doctrine\ORM\EntityManager;

class LoginManager
{
    private string $USER_ID = "USER_ID";

    public function __construct(private UserManager $userManager, private SessionManager $sessionManager, private EntityManager $entityManager) {}

    public function login(string $usernameOrEmail, string $password, bool $isRemember): ?User
    {
        $user = $this->userManager->findByUsername($usernameOrEmail);
        if (!isset($user)) $user = $this->userManager->findByEmail($usernameOrEmail);
        if (!isset($user)) return null;

        $result = password_verify($password, $user->passwordHash) ? $user : null;
        if ($result !== null) {
            $this->sessionManager->setPersistentEntry($this->USER_ID, $user->id);
        }
        return $result;
    }

    public function externalLogin(string $email): ?User
    {
        $user = $this->userManager->findByEmail($email);
        if ($user === null) return null;
        if (!$user->isVerifiedEmail) {
            $user->isVerifiedEmail = true;
            $this->entityManager->flush();
        }

        $this->sessionManager->setPersistentEntry($this->USER_ID, $user->id);
        return $user;
    }

    public function getCurrentUser(): ?User
    {
        $userId = $this->sessionManager->getPersistentEntry($this->USER_ID);
        return $userId !== null ? $this->userManager->findById($userId) : null;
    }

    public function getCurrentUserId(): ?int
    {
        return $this->sessionManager->getPersistentEntry($this->USER_ID);
    }


    public function isLoggedIn(): bool
    {
        $userId = $this->sessionManager->getPersistentEntry($this->USER_ID);
        return $userId !== null;
    }

    public function logout(): void
    {
        $this->sessionManager->remove();
    }
}
