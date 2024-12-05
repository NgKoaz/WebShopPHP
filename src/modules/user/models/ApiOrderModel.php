<?php

namespace App\modules\user\models;

use App\core\Attributes\Validator\Required;
use App\core\Model;

class ApiOrderModel extends Model
{
    #[Required()]
    public ?string $billId = null;
}
