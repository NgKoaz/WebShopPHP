<?php

namespace App\services;

use App\Entities\Product;
use DateTime;
use Doctrine\ORM\EntityManager;

class ProductManager
{
    public function __construct(private EntityManager $entityManager) {}

    public function getProductsWithNumPage(int $page, int $limit): array
    {
        $count = $this->entityManager->getRepository(Product::class)->count([]);
        $totalPages = ceil($count / $limit);
        $page = ($page < 1) ? 1 : $page;
        $offset = ($page - 1) * $limit;
        $products = $this->entityManager->getRepository(Product::class)->findBy(criteria: [], limit: $limit, offset: $offset);
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

    public function getProductById(mixed $id): ?Product
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
        return $this->getProductById($id) !== null;
    }

    public function createProduct(string $name, string $description, string $price, int $quantity, string $slug): void
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

        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }

    public function editProduct(mixed $id, string $name, string $description, string $price, string $quantity, string $slug, bool $isDeleted)
    {
        $product = $this->getProductById($id);
        $product->name = $name;
        $product->description = $description;
        $product->price = $price;
        $product->quantity = $quantity;
        $product->slug = $slug;
        $product->isDeleted = $isDeleted;

        $this->entityManager->flush();
    }

    public function deleteProduct(mixed $id)
    {
        $product = $this->getProductById($id);
        $this->entityManager->remove($product);
        $this->entityManager->flush();
    }
}
