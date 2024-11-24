<?php

namespace App\modules\user\controllers;

use App\core\Attributes\Http\HttpGet;
use App\core\Attributes\Http\HttpPost;
use App\core\Controller;
use App\modules\user\models\ReviewModel;
use App\services\LoginManager;
use App\services\ProductManager;
use App\services\ReviewManager;

class UserApiReviewController extends Controller
{
    public function __construct(private LoginManager $loginManager, private ProductManager $productManager, private ReviewManager $reviewManager) {}

    #[HttpPost("/api/reviews")]
    public function postReview(ReviewModel $model)
    {
        if ($model->isValid()) {
            $isError = false;

            if (!$this->productManager->hasId($model->productId)) {
                $model->setError("productId", "Product id is not found!");
                $isError = true;
            }

            if (!$this->loginManager->isLoggedIn()) {
                $model->setError("user", "User hasn't logged in yet!");
                $isError = true;
            }

            if (!$isError) {
                $this->reviewManager->addComment($model->productId, $model->rate, $model->comment);
                $review = $this->reviewManager->findByUserIdAndProductId($this->loginManager->getCurrentUserId(), $model->productId);
                return $this->json($review);
            }
        }
        return $this->json(["code" => 404, "errors" => $model->getFullError()], 400);
    }

    #[HttpGet("/api/reviews")]
    public function getReviews(int $page = 1, int $limit = 4, int $productId = 0)
    {
        if ($productId === 0)  return $this->json(["code" => 404, "errors" => ["message" => "Product id is not found"]], 400);
        $reviews = $this->reviewManager->getReviews($page, $limit, $productId);
        return $this->json($reviews);
    }
}
