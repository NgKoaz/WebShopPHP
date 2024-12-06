<?php

namespace App\modules\user\models;

use App\core\Attributes\Validator\Required;
use App\core\Model;

class ChangePasswordModel extends Model
{
    #[Required()]
    public ?string $currentPassword = null;

    #[Required()]
    public ?string $newPassword = null;
}
