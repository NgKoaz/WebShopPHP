<?php

namespace App\modules\admin\models;

use App\core\Attributes\Validator\Email;
use App\core\Attributes\Validator\Phone;
use App\core\Attributes\Validator\Required;
use App\core\Model;

class CreateUserModel extends Model
{
    #[Required()]
    public ?string $firstname = null;

    #[Required()]
    public ?string $lastname = null;

    #[Required()]
    public ?string $username = null;

    #[Required()]
    #[Email()]
    public ?string $email = null;

    #[Required()]
    #[Phone()]
    public ?string $phone = null;

    #[Required()]
    public ?string $password = null;
}
