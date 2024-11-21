<?php

namespace App\modules\auth\models;

use App\core\Attributes\Validator\Required;
use App\core\Attributes\Validator\StringLength;
use App\core\Model;

class LoginModel extends Model
{
    #[Required()]
    #[StringLength(min: 2, max: 30)]
    public ?string $username = null;

    #[Required()]
    #[StringLength(min: 2, max: 30)]
    public ?string $password = null;

    public ?string $rememberMe = null;
}
