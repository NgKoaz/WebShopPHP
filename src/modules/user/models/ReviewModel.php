<?php

namespace App\modules\user\models;

use App\core\Attributes\Validator\Required;
use App\core\Model;

class ReviewModel extends Model
{
    #[Required()]
    public ?string $rate = null;

    #[Required()]
    public ?string $comment = null;

    #[Required()]
    public ?string $productId = null;
}
