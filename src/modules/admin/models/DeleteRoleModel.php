<?php

namespace App\modules\admin\models;

use App\core\Attributes\Validator\Number;
use App\core\Attributes\Validator\Required;
use App\core\Model;

class DeleteRoleModel extends Model
{
    #[Required()]
    #[Number()]
    public ?string $id = null;
}
