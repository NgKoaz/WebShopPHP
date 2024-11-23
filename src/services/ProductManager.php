<?php

namespace App\services;

use App\Entities\Category;
use App\Entities\Product;
use DateTime;
use Doctrine\ORM\EntityManager;
use PgSql\Lob;

class ProductManager
{
    public function __construct(private EntityManager $entityManager, private CategoryManager $categoryManager) {}

    public function getProductsWithNumPage(int $page, int $limit): array
    {
        $count = $this->entityManager->getRepository(Product::class)->count([]);
        $totalPages = ceil($count / $limit);
        $page = ($page < 1) ? 1 : $page;
        $offset = ($page - 1) * $limit;
        $products = $this->entityManager->getRepository(Product::class)->findBy(criteria: [], limit: $limit, offset: $offset);

        $categories = $this->categoryManager->getObjectCategories() ?? [];
        // var_dump($products);
        foreach ($products as $product) {
            if (isset($product->categoryId)) {
                foreach ($categories as $category) {
                    if ($product->categoryId == $category->id) {
                        $product->category = $category;
                    }
                }
            }
        }

        return [
            "products" => $products,
            "totalPages" => $totalPages,
            "currentPage" => $page
        ];
    }

    public function findAllByCategoryId(int $categoryId): array
    {
        $products = $this->entityManager->createQueryBuilder()
            ->select('p')
            ->from(Product::class, 'p')
            ->where('p.categoryId = :categoryId')
            ->setParameter('categoryId', $categoryId)
            ->getQuery()
            ->getResult();
        return $products;
    }

    public function getProductsComplex(int $page, int $limit, string $query, array $options): array
    {
        $count = $this->entityManager
            ->getRepository(Product::class)
            ->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->where('LOWER(p.name) LIKE :name')
            ->setParameter('name', '%' . strtolower($query) . '%')
            ->getQuery()
            ->getSingleScalarResult();

        $totalPages = ceil($count / $limit);
        $page = ($page < 1) ? 1 : $page;
        $page = ($page > $totalPages) ? $totalPages : $page;
        $offset = ($page - 1) * $limit;


        $productsQuery = $this->entityManager
            ->getRepository(Product::class)
            ->createQueryBuilder('p')
            ->where('LOWER(p.name) LIKE :name')
            ->setParameter('name', '%' . strtolower($query) . '%')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        $order = isset($options["order"]) ? $options["order"] : null;
        if ($order !== null) {
            switch ($order) {
                case "createdAt":
                    $productsQuery->orderBy("p.$order", 'DESC');
                    break;
            }
        }

        $products = $productsQuery
            ->getQuery()
            ->getResult();

        return [
            "products" => $products,
            "totalPages" => $totalPages,
            "currentPage" => $page
        ];
    }

    public function getProductBySlug(mixed $slug): ?Product
    {
        return  $this->entityManager->getRepository(Product::class)->findOneBy(["slug" => $slug]);
    }

    public function findProductById(mixed $id): ?Product
    {
        return $this->entityManager->getRepository(Product::class)->findOneBy(["id" => $id]);
    }

    public function hasSlug(string $slug): bool
    {
        return $this->getProductBySlug($slug) !== null;
    }

    public function hasSlugWithId(mixed $id, string $slug): bool
    {
        $product = $this->entityManager->getRepository(Product::class)->findOneBy(["slug" => $slug]);
        return $product === null || $product->id == +$id;
    }

    public function hasId(string $id): bool
    {
        return $this->findProductById($id) !== null;
    }

    public function createProduct(string $name, string $description, string $price, int $quantity, string $slug, string $categoryId): void
    {
        $product = new Product;
        $product->name = $name;
        $product->description = $description;
        $product->price = $price;
        $product->quantity = $quantity;
        $product->rate = 0;
        $product->slug = $slug;
        $product->isDeleted = false;
        $now = new DateTime;
        $product->createdAt = $product->updatedAt = $now;

        $product->category = $this->categoryManager->findById($categoryId);
        $product->categoryId = $categoryId;

        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }

    public function editProduct(mixed $id, string $name, string $description, string $price, string $quantity, string $slug, bool $isDeleted, string $categoryId)
    {
        $product = $this->findProductById($id);
        $product->name = $name;
        $product->description = $description;
        $product->price = $price;
        $product->quantity = $quantity;
        $product->slug = $slug;
        $product->isDeleted = $isDeleted;

        $product->category = $this->categoryManager->findById($categoryId);
        $product->categoryId = $categoryId;

        $this->entityManager->flush();
    }

    public function deleteProduct(mixed $id)
    {
        $product = $this->findProductById($id);
        $this->entityManager->remove($product);
        $this->entityManager->flush();
    }

    public function findProductsByName(string $name): array
    {
        return $this->entityManager
            ->getRepository(Product::class)
            ->createQueryBuilder('p')
            ->where('LOWER(p.name) LIKE :name')
            ->setParameter('name', '%' . strtolower($name) . '%') // Partial match
            ->getQuery()
            ->getResult();
    }
}
