<?php

namespace App\modules\user\models;

use App\core\Attributes\Validator\Number;
use App\core\Attributes\Validator\Required;
use App\core\Model;

class DeleteCartModel extends Model
{
    #[Required()]
    #[Number()]
    public ?string $productId = null;
}
