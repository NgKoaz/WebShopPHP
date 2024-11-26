<?php

namespace App\modules\admin\models;

use App\core\Attributes\Validator\Required;
use App\core\Model;
use App\core\Types\HTMLString;

class EditProductDetailsModel extends Model
{
    #[Required()]
    public ?string $id = null;

    public HTMLString $details;

    public function __construct()
    {
        $this->details = new HTMLString();
    }
}
