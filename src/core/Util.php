<?php

namespace App\core;

class Util
{
    public static function getShortNameOfClass(string $longName): string
    {
        $parts = explode("\\", $longName);
        return count($parts) > 0 ? array_pop($parts) : "";
    }

    public static function getFirstInArray(array $array): mixed
    {
        if (is_array($array) || count($array) > 0) return $array[0];
        return null;
    }
}
