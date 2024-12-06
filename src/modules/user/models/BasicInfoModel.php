<?php

namespace App\modules\user\models;

use App\core\Attributes\Validator\Required;
use App\core\Model;

class BasicInfoModel extends Model
{
    #[Required()]
    public ?string $firstname = null;

    #[Required()]
    public ?string $lastname = null;

    #[Required()]
    public ?string $address = null;
}
