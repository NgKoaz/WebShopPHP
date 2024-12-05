<?php

namespace App\modules\auth\models;

use App\core\Attributes\Validator\Required;
use App\core\Model;

class ResetPasswordModel extends Model
{
    #[Required()]
    public ?string $password = null;

    public ?string $token = null;

    public ?string $email = null;
}
