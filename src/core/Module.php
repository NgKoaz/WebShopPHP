<?php

namespace App\core;

abstract class Module
{
    /**
     * @return Controller[]
     */
    public abstract function getControllers(): array;
}
