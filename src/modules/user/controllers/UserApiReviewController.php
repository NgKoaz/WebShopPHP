<?php

namespace App\modules\user\controllers;

use App\core\Attributes\Http\HttpGet;
use App\core\Attributes\Http\HttpPost;
use App\core\Controller;
use App\core\Util\ArrayHelper;
use App\Middleware\Auth;
use App\modules\user\models\ReviewModel;
use App\services\LoginManager;
use App\services\ProductManager;
use App\services\ReviewManager;
use DateTime;

class UserApiReviewController extends Controller
{
    public function __construct(private LoginManager $loginManager, private ProductManager $productManager, private ReviewManager $reviewManager) {}

    #[Auth("/api/errors/unauthorize")]
    #[HttpPost("/api/reviews")]
    public function postReview(ReviewModel $model)
    {
        if ($model->isValid()) {
            $isError = false;

            if (!$this->productManager->hasId($model->productId)) {
                $model->setError("productId", "Product id is not found!");
                $isError = true;
            }

            $user = $this->loginManager->getCurrentUser();
            $productId = $model->productId;

            $canReviews = isset($user->canReviews) && strlen($user->canReviews) > 0 ? $user->canReviews : "[]";
            $canReviews = json_decode($canReviews, true);

            $isAllow = ArrayHelper::some($canReviews, function ($element) use ($productId) {
                if (new DateTime($element["expiredAt"]) >= new DateTime && +$element["productId"] == +$productId) {
                    return true;
                }
                return false;
            });
            if (!$isAllow) return $this->json(["code" => 400, "message" => "Just allow review when you have already bought one!"], 400);

            if (!$isError) {
                $this->reviewManager->removePermissionReview($productId);
                $this->reviewManager->addComment($model->productId, $model->rate, $model->comment);
                return $this->json(["code" => 200, "message" => "Your review has been received!"]);
            }
        }
        return $this->json(["code" => 400, "message" => $model->getSerializedErrorMessage()], 400);
    }

    #[HttpGet("/api/reviews")]
    public function getReviews(int $page = 1, int $limit = 4, int $productId = 0)
    {
        if ($productId === 0)  return $this->json(["code" => 404, "errors" => ["message" => "Product id is not found"]], 400);
        $reviews = $this->reviewManager->getReviews($page, $limit, $productId);
        return $this->json($reviews);
    }
}
