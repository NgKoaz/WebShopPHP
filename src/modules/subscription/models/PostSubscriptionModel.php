<?php

namespace App\modules\subscription\models;

use App\core\Attributes\Validator\Email;
use App\core\Attributes\Validator\Required;
use App\core\Model;

class PostSubscriptionModel extends Model
{
    #[Required()]
    #[Email()]
    public ?string $email = null;
}
