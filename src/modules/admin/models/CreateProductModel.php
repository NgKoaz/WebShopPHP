<?php

namespace App\modules\admin\models;

use App\core\Attributes\Validator\Number;
use App\core\Attributes\Validator\Required;
use App\core\Attributes\Validator\Slug;
use App\core\Model;

class CreateProductModel extends Model
{
    #[Required()]
    public ?string $name = null;

    #[Required()]
    public ?string $description = null;

    #[Required()]
    #[Number()]
    public ?string $price = null;

    #[Required()]
    #[Number()]
    public ?string $quantity = null;

    #[Required()]
    #[Slug()]
    public ?string $slug = null;

    #[Required()]
    #[Number()]
    public ?string $categoryId = null;
}
