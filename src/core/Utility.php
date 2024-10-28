<?php

namespace App\core;

class Utility
{
    public static function getShortNameOfClass(string $longName): string
    {
        $parts = explode("\\", $longName);
        return count($parts) > 0 ? array_pop($parts) : "";
    }
}
