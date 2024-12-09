<?php

namespace App\core\Util;


class ArrayHelper
{
    public function __construct() {}

    public static function firstOrDefault(array $arr, mixed $defaultValue = null): mixed
    {
        return (count($arr) > 0) ? $arr[0] : $defaultValue;
    }

    public static function findOne(array $arr, callable $predicate): mixed
    {
        foreach ($arr as $element) if ($predicate($element)) return $element;
    }

    public static function forEach(array $arr, callable $callback): void
    {
        foreach ($arr as $element) $callback($element);
    }

    public static function forEachKeyValue(array $arr, callable $callback): void
    {
        foreach ($arr as $key => $value) $callback($key, $value);
    }

    public static function map(array $arr, callable $callback): array
    {
        $newArr = [];
        foreach ($arr as $element) $newArr[] = $callback($element);
        return $newArr;
    }

    public static function mapKeyValue(array $arr, callable $callback): array
    {
        $newArr = [];
        foreach ($arr as $key => $value) $newArr[] = $callback($key, $value);
        return $newArr;
    }

    public static function some(array $arr, callable $callback): bool
    {
        foreach ($arr as $element) if ($callback($element)) return true;
        return false;
    }
}
