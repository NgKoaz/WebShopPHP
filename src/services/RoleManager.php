<?php

namespace App\services;

use App\Entities\Role;
use App\Entities\User;
use Doctrine\ORM\EntityManager;

class RoleManager
{
    public function __construct(private EntityManager $entityManager) {}

    public function getUserRoles(User $user)
    {
        $roleIds = json_decode($user->roles, true);
        if (is_array($roleIds) && count($roleIds) > 0)
            return $this->entityManager->getRepository(Role::class)->findBy(["id" => $roleIds]);
        return null;
    }
}
