<?php

namespace App\services;

use App\Entities\User;

class LoginManager
{
    private string $USER_ID = "USER_ID";

    public function __construct(private UserManager $userManager, private SessionManager $sessionManager) {}

    public function login(string $usernameOrEmail, string $password): ?User
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

    public function getCurrentUser(): ?User
    {
        $userId = $this->sessionManager->getPersistentEntry($this->USER_ID);
        return $userId !== null ? $this->userManager->findById($userId) : null;
    }

    public function isLoggedIn(): bool
    {
        $userId = $this->sessionManager->getPersistentEntry($this->USER_ID);
        return isset($userId);
    }

    public function logout(): void
    {
        $this->sessionManager->removePersistent();
    }
}
