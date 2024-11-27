<?php

namespace App\modules\admin\models;

use App\core\Attributes\Validator\Required;
use App\core\Model;

class DeleteImageModel extends Model
{
    #[Required()]
    public ?int $productId = null;

    #[Required()]
    public ?string $image = null;
}
