<?php

namespace App\core\Types;


class HTMLString
{
    private string $str = "";

    public function __construct(string $content = "")
    {
        $this->str = $content;
    }

    public function append(string $content): void
    {
        $this->str .= $content;
    }

    public function __toString(): string
    {
        return $this->str;
    }
}
