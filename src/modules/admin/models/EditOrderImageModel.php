<?php

namespace App\modules\admin\models;

use App\Validator\MyJsonImages;
use App\core\Attributes\Validator\Required;
use App\core\Model;

class EditOrderImageModel extends Model
{
    #[Required()]
    public ?int $productId = null;

    #[Required()]
    #[MyJsonImages()]
    public ?string $images = null;
}
