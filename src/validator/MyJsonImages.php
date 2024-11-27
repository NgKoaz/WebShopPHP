<?php

namespace App\Validator;

use App\core\Attributes\Validator\Validator;
use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class MyJsonImages extends Validator
{
    public function __construct(string $errorMessage = "Wrong images format!")
    {
        parent::__construct($errorMessage);
    }

    public function isValid($property): bool
    {
        if ($images = json_decode($property, true)) {
            foreach ($images as $image) {
                if (!is_array($image) || !array_key_exists("sm", $image) || !array_key_exists("lg", $image)) {
                    return false;
                }
                if (!is_string($image["sm"]) || !is_string($image["lg"])) {
                    return false;
                }
            }
            return true;
        }

        return false;
    }
}
