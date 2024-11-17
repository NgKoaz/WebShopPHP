<?php

namespace App\modules\admin\models;

use App\core\Attributes\Validator\Phone;
use App\core\Attributes\Validator\Required;
use App\core\Model;

class EditUserModel extends Model
{
    #[Required()]
    public int $id;

    #[Required()]
    public string $firstname;

    #[Required()]
    public string $lastname;

    #[Required()]
    #[Phone()]
    public string $phone;

    public string $password;

    #[Required()]
    public bool $isDeleted;
}
