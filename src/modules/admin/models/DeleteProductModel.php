<?php

namespace App\modules\admin\models;

use App\core\Attributes\Validator\Required;
use App\core\Model;

class DeleteProductModel extends Model
{
    #[Required()]
    public ?int $id = null;
}
