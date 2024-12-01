<?php

namespace App\modules\auth\models;

use App\core\Attributes\Validator\Email;
use App\core\Attributes\Validator\Phone;
use App\core\Attributes\Validator\Required;
use App\core\Attributes\Validator\StringLength;
use App\core\Model;

class RegisterModel extends Model
{
    #[Required()]
    #[StringLength(min: 2, max: 20)]
    public ?string $firstname = null;

    #[Required()]
    #[StringLength(min: 2, max: 20)]
    public ?string $lastname = null;

    #[Required()]
    #[StringLength(min: 6, max: 30)]
    public ?string $username = null;

    #[Required()]
    #[Email]
    public ?string $email = null;

    #[Required()]
    #[Phone()]
    public ?string $phone = null;

    #[Required()]
    #[StringLength(min: 6, max: 30)]
    public ?string $password = null;
}
