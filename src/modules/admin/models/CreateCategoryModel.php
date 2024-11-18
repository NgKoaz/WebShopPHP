<?php

namespace App\modules\admin\models;

use App\core\Attributes\Validator\Required;
use App\core\Attributes\Validator\Slug;
use App\core\Model;

class CreateCategoryModel extends Model
{
    #[Required()]
    public ?string $name = null;

    #[Required()]
    #[Slug()]
    public ?string $slug = null;

    public ?string $parentId = null;
}
