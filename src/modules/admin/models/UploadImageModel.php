<?php

namespace App\modules\admin\models;

use App\core\Attributes\Validator\Required;
use App\core\Model;
use App\core\Types\Image;

class UploadImageModel extends Model
{
    #[Required()]
    public ?int $productId = null;

    #[Required()]
    public Image $image;

    public function __construct()
    {
        $this->image = new Image;
    }
}
