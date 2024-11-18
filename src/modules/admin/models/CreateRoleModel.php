<?php

namespace App\modules\admin\models;

use App\core\Attributes\Validator\Required;
use App\core\Model;

class CreateRoleModel extends Model
{
    #[Required()]
    public ?string $name = null;
}
