<?php

namespace App\modules\admin\models;

use App\core\Attributes\Validator\Email;
use App\core\Attributes\Validator\Phone;
use App\core\Attributes\Validator\Required;
use App\core\Model;

class DeleteUserModel extends Model
{
    #[Required()]
    public int $id;
}
