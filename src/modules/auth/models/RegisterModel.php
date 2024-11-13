<?php

namespace App\modules\auth\models;

use App\core\Attributes\Validator\Email;
use App\core\Attributes\Validator\StringLength;
use App\core\Model;

class RegisterModel extends Model
{
    #[StringLength(min: 2, max: 20)]
    public string $firstName;

    #[StringLength(min: 2, max: 20)]
    public string $lastName;

    #[StringLength(min: 6, max: 30)]
    public string $username;

    #[Email]
    public string $email;

    public string $phone;

    #[StringLength(min: 6, max: 30)]
    public string $password;

    public string $confirmPassword;
}
