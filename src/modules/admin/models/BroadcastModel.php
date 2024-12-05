<?php

namespace App\modules\admin\models;

use App\core\Attributes\Validator\Required;
use App\core\Model;
use App\core\Types\HTMLString;

class BroadcastModel extends Model
{
    public HTMLString $content;

    public function __construct()
    {
        $this->content = new HTMLString();
    }
}
