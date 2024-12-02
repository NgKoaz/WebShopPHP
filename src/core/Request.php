<?php

namespace App\core;

use App\Entities\User;
use App\services\LoginManager;
use App\services\RoleManager;

class Request
{
    public function __construct(private LoginManager $loginManager, private RoleManager $roleManager) {}

    public function getCurrentUser(): ?User
    {
        return $this->loginManager->getCurrentUser();
    }

    public function hasUserLoggedIn(): bool
    {
        return $this->loginManager->isLoggedIn();
    }

    public function isUserHasRoles(array $roles): bool
    {
        return $this->roleManager->isUserHasRoles($this->getCurrentUser(), $roles);
    }
}
