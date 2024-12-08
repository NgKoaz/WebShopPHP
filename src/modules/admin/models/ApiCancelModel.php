<?php

namespace App\modules\admin\models;

use App\core\Attributes\Validator\Required;
use App\core\Model;

class ApiCancelModel extends Model
{
    #[Required()]
    public ?string $billId = null;
}
