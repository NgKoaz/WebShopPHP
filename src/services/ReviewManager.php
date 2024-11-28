<?php

namespace App\services;

use App\Entities\Review;
use DateTime;
use Doctrine\ORM\EntityManager;

class ReviewManager
{
    public function __construct(private EntityManager $entityManager, private LoginManager $loginManager, private ProductManager $productManager) {}

    public function addComment(int $productId, int $rate, string $comment)
    {
        $user = $this->loginManager->getCurrentUser();

        $review = new Review;
        $review->rate = $rate;
        $review->comment = $comment;
        $review->userId = $user->id;
        $review->user = $user;
        $review->productId = $productId;
        $review->product = $this->productManager->findProductById($productId);
        $review->product->totalRates += $rate;
        $review->product->totalReviews += 1;
        $review->isDeleted = false;
        $now = new DateTime;
        $review->createdAt = $review->updatedAt = $now;

        $this->entityManager->persist($review);
        $this->entityManager->flush();
    }

    public function findByUserIdAndProductId(int $userId, int $productId)
    {
        return $this->entityManager->getRepository(Review::class)->findOneBy(["userId" => $userId, "productId" => $productId]);
    }

    public function getReviews(int $page, int $limit, int $productId)
    {

        $sql = "
            SELECT COUNT(*)
            FROM reviews r
            WHERE r.product_id = $productId";

        $productQuery = $this->entityManager->getConnection()->prepare($sql);
        $stmt = $productQuery->executeQuery();
        $count = $stmt->fetchOne();
        // END GET COUNT 


        $totalPages = (int) ceil($count / $limit);
        $page = ($page < 1) ? 1 : $page;
        $page = ($page > $totalPages) ? $totalPages : $page;
        $offset = ($page - 1) * $limit;
        $offset = $offset < 0 ? 0 : $offset;

        $sql = "
            SELECT r.*, u.username
            FROM reviews r 
            JOIN users u ON u.id = r.user_id
            WHERE r.product_id = $productId
            LIMIT " . intval($limit) . "
            OFFSET " . intval($offset) . "
        ";

        $productQuery = $this->entityManager->getConnection()->prepare($sql);
        $stmt = $productQuery->executeQuery();
        $reviews = $stmt->fetchAllAssociative();

        return [
            "reviews" => $reviews,
            "totalPages" => $totalPages,
            "currentPage" => $page,
            "count" => $count
        ];
    }
}
