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

    public function hasRoles(array $ids)
    {
        if (count($ids) <= 0) return true;

        $count = $this->entityManager->createQueryBuilder()
            ->select('COUNT(r.id)')
            ->from(Role::class, 'r')
            ->where('r.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getSingleScalarResult();

        return +$count === count($ids);
    }

    public function findById(mixed $id): ?Role
    {
        return $this->entityManager->getRepository(Role::class)->findOneBy(["id" => $id]);
    }

    public function findByName(string $name): ?Role
    {
        return $this->entityManager->getRepository(Role::class)->findOneBy(["name" => $name]);
    }

    public function hasName(string $name): bool
    {
        return $this->findByName($name) !== null;
    }

    public function hasId(mixed $id): bool
    {
        return $this->findById($id) !== null;
    }

    public function hasNameWithId(mixed $id, string $name): bool
    {
        $role = $this->findByName($name);
        if ($role === null) return true;
        return $role->id == +$id;
    }

    public function getRoles(): array
    {
        return $this->entityManager->getRepository(Role::class)->findAll([]);
    }

    public function createRole(string $name): void
    {
        $role = new Role;
        $role->name = $name;
        $this->entityManager->persist($role);
        $this->entityManager->flush();
    }

    public function updateRole(mixed $id, string $name): void
    {
        $role = $this->findById($id);
        $role->name = $name;
        $this->entityManager->flush();
    }

    public function deleteRole(mixed $id): void
    {
        $role = $this->findById($id);

        $this->entityManager->remove($role);
        $this->entityManager->flush();
    }
}
