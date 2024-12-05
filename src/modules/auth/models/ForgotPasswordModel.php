<?php

namespace App\modules\auth\models;

use App\core\Attributes\Validator\Email;
use App\core\Attributes\Validator\Required;
use App\core\Model;

class ForgotPasswordModel extends Model
{
    #[Required()]
    #[Email]
    public ?string $email = null;
}
