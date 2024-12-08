<?php

namespace App\core\Util;


class ArrayHelper
{
    public function __construct() {}

    public static function firstOrDefault(array $arr, mixed $defaultValue = null)
    {
        return (count($arr) > 0) ? $arr[0] : $defaultValue;
    }
}
