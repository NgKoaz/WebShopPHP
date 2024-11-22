<?php

namespace App\modules\user\models;

use App\core\Attributes\Validator\Number;
use App\core\Attributes\Validator\Required;
use App\core\Model;

class EditCartModel extends Model
{
    #[Required()]
    #[Number()]
    public ?string $productId = null;

    #[Required()]
    #[Number()]
    public ?string $quantity = null;
}
