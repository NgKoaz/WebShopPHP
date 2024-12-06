<?php

namespace App\modules\user\models;

use App\core\Attributes\Validator\Required;
use App\core\Model;

class ChangeEmailModel extends Model
{
    #[Required()]
    public ?string $email = null;
}
