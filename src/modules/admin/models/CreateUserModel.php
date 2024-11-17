<?php

namespace App\modules\admin\models;

use App\core\Attributes\Validator\Email;
use App\core\Attributes\Validator\Phone;
use App\core\Attributes\Validator\Required;
use App\core\Model;

class CreateUserModel extends Model
{
    #[Required()]
    public string $firstname;

    #[Required()]
    public string $lastname;

    #[Required()]
    public string $username;

    #[Required()]
    #[Email()]
    public string $email;

    #[Required()]
    #[Phone()]
    public string $phone;

    #[Required()]
    public string $password;
}
