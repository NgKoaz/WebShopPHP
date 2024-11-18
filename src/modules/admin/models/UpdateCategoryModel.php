<?php

namespace App\modules\admin\models;

use App\core\Attributes\Validator\Number;
use App\core\Attributes\Validator\Required;
use App\core\Attributes\Validator\Slug;
use App\core\Model;

class UpdateCategoryModel extends Model
{
    #[Required()]
    #[Number()]
    public ?string $id = null;

    #[Required()]
    public ?string $name = null;

    #[Required()]
    #[Slug()]
    public ?string $slug = null;

    public ?string $parentId = null;
}
