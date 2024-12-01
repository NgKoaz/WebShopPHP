<?php

namespace App\services;

use App\Entities\Category;
use Doctrine\ORM\EntityManager;

class CategoryManager
{
    public function __construct(private EntityManager $entityManager) {}

    public function getCategories(): array
    {
        $query = $this->entityManager->createQuery(
            'SELECT c.id, c.name, c.slug, IDENTITY(c.parentCategory) AS parentId 
             FROM App\Entities\Category c'
        );
        return $query->getResult();
    }

    public function getObjectCategories(): array
    {
        return $this->entityManager->getRepository(Category::class)->findBy([]);
    }

    public function findBySlug(string $slug): ?Category
    {
        return $this->entityManager->getRepository(Category::class)->findOneBy(["slug" => $slug]);
    }

    public function findById(mixed $id): ?Category
    {
        return $this->entityManager->getRepository(Category::class)->findOneBy(["id" => $id]);
    }

    public function hasSlug(string $slug): bool
    {
        return $this->findBySlug($slug) !== null;
    }

    public function hasId(mixed $id): bool
    {
        return $this->findById($id) !== null;
    }

    public function hasSlugWithId(mixed $id, string $slug): bool
    {
        $category = $this->findBySlug($slug);
        if ($category === null) return true;
        return $category->id === +$id;
    }


    public function createCategory(?string $name, ?string $slug, ?string $parentId): void
    {
        $category = new Category;
        if ($name !== null)
            $category->name = $name;
        if ($slug !== null)
            $category->slug = $slug;
        if ($parentId !== null && strlen($parentId) > 0) {
            $category->parentId = +$parentId;
            $category->parentCategory = $this->findById(+$parentId);
        }
        $this->entityManager->persist($category);
        $this->entityManager->flush();
    }

    public function updateCategory(string $id, string $name, string $slug, ?string $parentId): void
    {
        $category = $this->findById($id);
        $category->name = $name;
        $category->slug = $slug;

        if ($parentId !== null && strlen($parentId) > 0 && +$parentId !== $category->parentId) {
            $oldParent = $category->parentCategory;

            // New parent
            $category->parentId = +$parentId;
            $category->parentCategory = $this->findById(+$parentId);

            $oldParentId = ($oldParent !== null) ? $oldParent->id : null;

            $childs = $category->subcategories;
            foreach ($childs as $child) {
                $child->parentId = $oldParentId;
                $child->parentCategory = $oldParent;
            }
        }
        $this->entityManager->flush();
    }

    public function deleteCategory(string $id)
    {
        $category = $this->findById($id);
        $parent = $category->parentCategory;
        $parentId = ($parent !== null) ? $parent->id : null;
        $subs = $category->subcategories;
        foreach ($subs as $sub) {
            $sub->parentId = $parentId;
            $sub->parentCategory = $parent;
        }
        $this->entityManager->remove($category);
        $this->entityManager->flush();
    }

    public function getAncestors(int $categoryId): array
    {
        $categories = $this->getCategories();
        return array_reverse($this->backtrackGetAncestors($categories, $categoryId));
    }

    public function getSuccessors(int $categoryId): array
    {
        return $this->backtrackGetSuccessors($this->getCategories(), $categoryId);
    }

    private function backtrackGetSuccessors(array $categories, int $categoryId): array
    {
        $successors = [];
        foreach ($categories as $category) {
            if ($category['parentId'] == $categoryId) {
                $successors[] = $category;
                $successors = array_merge($successors, $this->backtrackGetSuccessors($categories, $category['id']));
            }
        }

        return $successors;
    }

    private function backtrackGetAncestors(array $categories, int $categoryId, array &$path = []): array
    {
        // var_dump($categories);
        foreach ($categories as $category) {
            if ($category['id'] == +$categoryId) {
                $path[] = $category;
                if (isset($category['parentId'])) return $this->backtrackGetAncestors($categories, $category['parentId'], $path);
                break;
            }
        }
        return $path;
    }
}
