<?php

namespace App\modules\admin\models;

use App\core\Attributes\Validator\Email;
use App\core\Attributes\Validator\Required;
use App\core\Model;

class DeleteSubscriptionModel extends Model
{
    #[Required()]
    #[Email()]
    public ?string $email = null;
}
